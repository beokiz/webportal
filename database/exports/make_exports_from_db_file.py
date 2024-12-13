import os
import glob
import subprocess
import mysql.connector
from datetime import datetime
import pandas as pd
import requests

# Lokale Einstellungen
LOCAL_DUMP_PATH = os.path.expanduser("~/Downloads/2024-12-10-010003-dump-beokiz.sql")
CLEANED_DUMP_PATH = os.path.expanduser("~/Downloads/2024-12-10-010003-dump-beokiz_cleaned.sql")

DB_CONFIG = {
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "",
    "database": "beokiz_prod"
}

# Steuere, ob lokale Skripte verwendet werden
USE_LOCAL_SCRIPTS = True
LOCAL_SCRIPT_PATH = os.path.expanduser("./")  # Pfad zu lokalen SQL-Skripten
SQL_SCRIPT_URLS = [
    "https://raw.githubusercontent.com/beokiz/webportal/refs/heads/main/database/exports/export_Schulungen.sql",
    "https://raw.githubusercontent.com/beokiz/webportal/refs/heads/main/database/exports/export_Schulungen_und_Terminvorschlaege.sql"
]

def clean_dump_file(input_path, output_path):
    """Entfernt problematische Zeilen aus dem Dump."""
    print("Bereinige Dump-Datei...")
    with open(input_path, "r") as infile, open(output_path, "w") as outfile:
        for line in infile:
            if "/*!999999\\- enable the sandbox mode */" in line:
                continue
            outfile.write(line)
    print(f"Bereinigte Datei gespeichert unter: {output_path}")

def setup_local_database(dump_path):
    """Setzt die lokale Datenbank basierend auf dem Dump auf."""
    print("Setze lokale Datenbank auf...")
    connection = mysql.connector.connect(
        host=DB_CONFIG["host"],
        user=DB_CONFIG["user"],
        password=DB_CONFIG["password"]
    )
    cursor = connection.cursor()
    try:
        print("Dropping existing database...")
        cursor.execute("DROP DATABASE IF EXISTS beokiz_prod;")
        print("Creating new database...")
        cursor.execute("CREATE DATABASE beokiz_prod;")
        connection.commit()

        if not os.path.exists(dump_path):
            raise FileNotFoundError(f"Dump-Datei nicht gefunden: {dump_path}")

        print(f"Lade Datenbank-Dump aus: {dump_path}")
        restore_command = [
            "mysql",
            "-u", DB_CONFIG["user"],
            f"-p{DB_CONFIG['password']}",
            "beokiz_prod"
        ]
        with open(dump_path, "r") as dump_file:
            result = subprocess.run(restore_command, stdin=dump_file, text=True, capture_output=True)

        if result.returncode != 0:
            print(f"Fehler bei Restore: {result.stderr}")
            raise Exception(f"Restore fehlgeschlagen mit Rückgabecode {result.returncode}")
        print("Datenbank erfolgreich wiederhergestellt.")
    finally:
        cursor.close()
        connection.close()

def download_sql_script(url):
    """Lädt das SQL-Skript von der angegebenen URL herunter."""
    response = requests.get(url)
    if response.status_code == 200:
        return response.text
    else:
        raise Exception(f"Fehler beim Herunterladen des SQL-Skripts: {response.status_code}")

def load_local_sql_scripts(path):
    """Lädt alle lokalen SQL-Skripte aus dem angegebenen Pfad."""
    scripts = []
    for file_path in glob.glob(os.path.join(path, "*.sql")):
        with open(file_path, "r") as file:
            scripts.append((os.path.basename(file_path), file.read()))
    return scripts

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
            if not sql_scripts:  # Überprüfe, ob keine Skripte im Ordner sind
                raise FileNotFoundError(f"Keine SQL-Skripte im Ordner gefunden: {LOCAL_SCRIPT_PATH}")
        else:
            print("Lade SQL-Skripte aus URLs...")
            sql_scripts = [(os.path.basename(url), download_sql_script(url)) for url in SQL_SCRIPT_URLS]

        for script_name, sql_script in sql_scripts:
            output_file = f"{datetime.now().strftime('%Y-%m-%d')}_{script_name.replace('.sql', '')}.xlsx"
            execute_sql_and_export_to_excel(sql_script, DB_CONFIG, output_file)

    except FileNotFoundError as e:
        print(f"Fehler: {e}")
