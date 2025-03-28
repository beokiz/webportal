import os
import glob
import subprocess
import mysql.connector
from datetime import datetime
import pandas as pd
import requests
from dotenv import load_dotenv

# .env-Datei laden
load_dotenv()

# Lokale Einstellungen
LOCAL_DUMP_PATH = os.path.expanduser("~/Downloads/2025-03-18-010003-dump-beokiz.sql")
CLEANED_DUMP_PATH = os.path.splitext(LOCAL_DUMP_PATH)[0] + "_cleaned.sql"

DB_CONFIG = {
    "host": os.getenv("MYSQL_HOST", "localhost"),
    "port": int(os.getenv("MYSQL_PORT", 3306)),
    "user": os.getenv("MYSQL_USER"),
    "password": os.getenv("MYSQL_PASSWORD"),
    "database": os.getenv("MYSQL_DATABASE")
}

# Steuere, ob lokale Skripte verwendet werden
USE_LOCAL_SCRIPTS = True
LOCAL_SCRIPT_PATH = os.path.expanduser("./")  # Pfad zu lokalen SQL-Skripten
SQL_SCRIPT_URLS = [
    "https://raw.githubusercontent.com/beokiz/webportal/refs/heads/main/database/exports/export_Schulungen.sql",
    "https://raw.githubusercontent.com/beokiz/webportal/refs/heads/main/database/exports/export_Schulungen_und_Terminvorschlaege.sql"
]

def clean_dump_file(input_path, output_path):
    """Bereinigt den SQL-Dump: entfernt CREATE DATABASE, ersetzt 'USE beokiz' durch die Ziel-Datenbank."""
    print("Bereinige Dump-Datei...")
    target_db = DB_CONFIG["database"]
    with open(input_path, "r") as infile, open(output_path, "w") as outfile:
        for line in infile:
            # Entferne Sandbox-Zeile
            if "/*!999999\\- enable the sandbox mode */" in line:
                continue
            # Entferne CREATE DATABASE-Zeile
            if line.strip().startswith("CREATE DATABASE"):
                continue
            # Entferne Kommentar zur aktuellen DB
            if line.strip().startswith("-- Current Database:"):
                continue
            # Ersetze USE `beokiz` durch USE `beokiz_prod`
            if line.strip().startswith("USE `beokiz`"):
                line = f"USE `{target_db}`;\n"
            outfile.write(line)
    print(f"Bereinigte Datei gespeichert unter: {output_path}")

def setup_local_database(dump_path):
    """Setzt die lokale Datenbank basierend auf dem Dump auf und vergibt Berechtigungen."""
    print("Setze lokale Datenbank auf...")
    connection = mysql.connector.connect(
        host=DB_CONFIG["host"],
        user=DB_CONFIG["user"],
        password=DB_CONFIG["password"]
    )
    cursor = connection.cursor()
    try:
        db_name = DB_CONFIG["database"]
        print(f"Dropping existing database '{db_name}'...")
        cursor.execute(f"DROP DATABASE IF EXISTS {db_name};")
        print(f"Creating new database '{db_name}'...")
        cursor.execute(f"CREATE DATABASE {db_name} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")
        connection.commit()
    finally:
        cursor.close()
        connection.close()

    # Dump importieren mit mysql CLI
    if not os.path.exists(dump_path):
        raise FileNotFoundError(f"Dump-Datei nicht gefunden: {dump_path}")

    print(f"Lade Datenbank-Dump aus: {dump_path}")
    restore_command = [
        "mysql",
        "-u", DB_CONFIG["user"],
        f"-p{DB_CONFIG['password']}",
        db_name
    ]
    with open(dump_path, "r") as dump_file:
        result = subprocess.run(restore_command, stdin=dump_file, text=True, capture_output=True)

    if result.returncode != 0:
        print(f"Fehler bei Restore: {result.stderr}")
        raise Exception(f"Restore fehlgeschlagen mit Rückgabecode {result.returncode}")
    print("Datenbank erfolgreich wiederhergestellt.")

    # Rechte setzen (hier: root-Zugang erforderlich)
    print("Setze Benutzerrechte für die neue Datenbank...")
    try:
        root_password = os.getenv("MYSQL_ROOT_PASSWORD", "")

        root_connection = mysql.connector.connect(
            host=DB_CONFIG["host"],
            user="root",
            password=root_password
        )
        root_cursor = root_connection.cursor()
        root_cursor.execute(
            f"GRANT ALL PRIVILEGES ON {db_name}.* TO '{DB_CONFIG['user']}'@'localhost';"
        )
        root_cursor.execute("FLUSH PRIVILEGES;")
        print(f"Berechtigungen für '{DB_CONFIG['user']}' erfolgreich gesetzt.")
    except mysql.connector.Error as err:
        print(f"Fehler beim Setzen der Rechte: {err}")
    finally:
        if 'root_cursor' in locals():
            root_cursor.close()
        if 'root_connection' in locals():
            root_connection.close()

def verify_db_permissions(db_config, test_table="trainings"):
    """Testet, ob der User SELECT-Rechte auf die angegebene Tabelle hat."""
    print(f"Prüfe Zugriffsrechte auf Tabelle '{test_table}'...")
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()
        cursor.execute(f"SELECT COUNT(*) FROM {test_table} LIMIT 1;")
        count = cursor.fetchone()[0]
        print(f"Zugriff auf Tabelle '{test_table}' erfolgreich: {count} Einträge gefunden.")
    except mysql.connector.Error as err:
        print(f"\n❌ Fehler: Zugriff auf Tabelle '{test_table}' fehlgeschlagen.")
        print(f"Grund: {err}")
        print("\nBitte stelle sicher, dass der Benutzer die nötigen Rechte hat:")
        print(f"  GRANT ALL PRIVILEGES ON {db_config['database']}.* TO '{db_config['user']}'@'localhost';\n")
        raise
    finally:
        if 'cursor' in locals():
            cursor.close()
        if 'connection' in locals():
            connection.close()

def download_sql_script(url):
    """Lädt das SQL-Skript von der angegebenen URL herunter."""
    response = requests.get(url)
    if response.status_code == 200:
        return response.text
    else:
        raise Exception(f"Fehler beim Herunterladen des SQL-Skripts: {response.status_code}")

def load_local_sql_scripts(path):
    """Lädt und bereinigt alle lokalen SQL-Skripte aus dem angegebenen Pfad."""
    scripts = []
    for file_path in glob.glob(os.path.join(path, "*.sql")):
        with open(file_path, "r") as file:
            raw_sql = file.read()
            prepared_sql = prepare_sql_script(raw_sql)
            scripts.append((os.path.basename(file_path), prepared_sql))
    return scripts

def prepare_sql_script(raw_sql):
    """Ersetzt hartkodierte Datenbanknamen durch aktuelle DB."""
    return raw_sql.replace("beokiz.", f"{DB_CONFIG['database']}.")

def execute_sql_and_export_to_excel(sql_script, db_config, output_file):
    """Führt ein SQL-Skript aus und exportiert die Ergebnisse in eine Excel-Datei."""
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()

    try:
        statements = [stmt.strip() for stmt in sql_script.split(';') if stmt.strip()]
        for i, statement in enumerate(statements):
            cursor.execute(statement)
            while cursor.nextset():
                pass

        if statements:
            last_select = statements[-1]
            cursor.execute(last_select)
            results = cursor.fetchall()
            column_names = [desc[0] for desc in cursor.description]
            df = pd.DataFrame(results, columns=column_names)
            df.to_excel(output_file, index=False)
            print(f"Ergebnisse erfolgreich in '{output_file}' gespeichert.")
    finally:
        cursor.close()
        connection.close()

if __name__ == "__main__":
    try:
        # Prüfe und bereinige die Dump-Datei
        if os.path.exists(LOCAL_DUMP_PATH):
            clean_dump_file(LOCAL_DUMP_PATH, CLEANED_DUMP_PATH)
            setup_local_database(CLEANED_DUMP_PATH)
        else:
            raise FileNotFoundError(f"Dump-Datei nicht gefunden: {LOCAL_DUMP_PATH}")

        # SQL-Skripte ausführen
        if USE_LOCAL_SCRIPTS:
            print("Verwende lokale SQL-Skripte...")
            sql_scripts = load_local_sql_scripts(LOCAL_SCRIPT_PATH)
            if not sql_scripts:
                raise FileNotFoundError(f"Keine SQL-Skripte im Ordner gefunden: {LOCAL_SCRIPT_PATH}")
        else:
            print("Lade SQL-Skripte aus URLs...")
            sql_scripts = [(os.path.basename(url), download_sql_script(url)) for url in SQL_SCRIPT_URLS]

        verify_db_permissions(DB_CONFIG, test_table="trainings")

        for script_name, sql_script in sql_scripts:
            output_file = f"{datetime.now().strftime('%Y-%m-%d')}_{script_name.replace('.sql', '')}.xlsx"
            execute_sql_and_export_to_excel(sql_script, DB_CONFIG, output_file)

    except FileNotFoundError as e:
        print(f"Fehler: {e}")
