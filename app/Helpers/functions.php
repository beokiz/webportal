<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

/**
 * Common helper functions
 */

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpFoundation\IpUtils;

/*
|--------------------------------------------------------------------------
| Array Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('array_overwrite')) {
    /**
     * Equivalent of array_merge, but overwrites an existing key instead of merging.
     *
     * @param array $original  Array to be overwritten
     * @param array $overwrite Array to merge into $original
     * @param bool  $recursive
     * @return void
     */
    function array_overwrite(array &$original, array $overwrite, bool $recursive = false)
    {
        // Not included in function signature, so we can return silently if not an array
        if (!is_array($overwrite)) {
            return;
        }
        if (!is_array($original)) {
            $original = $overwrite;
        }

        foreach ($overwrite as $key => $value) {
            if ($recursive && array_key_exists($key, $original) && is_array($value)) {
                array_overwrite($original[$key], $overwrite[$key]);
            } else {
                $original[$key] = $value;
            }
        }
    }
}

if (!function_exists('array_from_object')) {
    /**
     * Recursively cast a PHP object to array.
     *
     * @param object $object
     * @return array
     */
    function array_from_object(object $object) : array
    {
        return json_decode(json_encode($object), true);
    }
}

if (!function_exists('in_array_all')) {
    /**
     * Check if ALL needles exist in array.
     *
     * @param array $needles
     * @param array $haystack
     * @return bool
     */
    function in_array_all(array $needles, array $haystack) : bool
    {
        return empty(array_diff($needles, $haystack));
    }
}

if (!function_exists('in_array_any')) {
    /**
     * Check if ANY of the needles exist in array.
     *
     * @param array $needles
     * @param array $haystack
     * @return bool
     */
    function in_array_any(array $needles, array $haystack) : bool
    {
        return !empty(array_intersect($needles, $haystack));
    }
}

if (!function_exists('is_array_homogeneous')) {
    /**
     * Checks if an array contains at most 1 distinct value.
     * Optionally, restrict what the 1 distinct value is permitted to be via
     * a user supplied testValue.
     *
     * @param array $array      - Array to check
     * @param null  $test_value - Optional value to restrict which distinct value the array is permitted to contain.
     * @return bool - false if the array contains more than 1 distinct value, or contains a value other than your supplied testValue.
     * @assert is_array_homogeneous([]) === true
     * @assert is_array_homogeneous([], 2) === true
     * @assert is_array_homogeneous([2]) === true
     * @assert is_array_homogeneous([2, 3]) === false
     * @assert is_array_homogeneous([2, 2]) === true
     * @assert is_array_homogeneous([2, 2], 2) === true
     * @assert is_array_homogeneous([2, 2], 3) === false
     * @assert is_array_homogeneous([2, 3], 3) === false
     * @assert is_array_homogeneous([null, null], null) === true
     */
    function is_array_homogeneous(array $array, $test_value = null) : bool
    {
        // If they did not pass the 2nd func argument, then we will use an arbitrary value in the $arr (that happens to be the first value).
        // By using func_num_args() to test for this, we can properly support testing for an array filled with nulls, if desired.
        // ie is_array_homogeneous([null, null], null) === true
        $test_value = func_num_args() > 1 ? $test_value : reset($array);

        foreach ($array as $value) {
            if ($test_value !== $value) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('sort_multidimensional_array')) {
    /**
     * @param array  $array
     * @param string $attribute
     * @return void
     */
    function sort_multidimensional_array(array &$array, string $attribute)
    {
        usort($array, function ($a, $b) use ($attribute) {
            if ($a[$attribute] == $b[$attribute]) {
                return 0;
            } elseif ($a[$attribute] < $b[$attribute]) {
                return -1;
            } else {
                return 1;
            }
        });
    }
}

if (!function_exists('filter_array_by_prefix')) {
    /**
     * @param array  $array
     * @param string $prefix
     * @return array
     */
    function filter_array_by_prefix(array $array, string $prefix) : array
    {
        $filteredArray = [];

        foreach ($array as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $filteredArray[$key] = $value;
            }
        }

        return $filteredArray;
    }
}


/*
|--------------------------------------------------------------------------
| Validation Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('is_timestamp')) {
    /**
     * Check if a string is a valid timestamp.
     *
     * @param mixed $timestamp
     * @return bool
     */
    function is_timestamp($timestamp) : bool
    {
        return (ctype_digit($timestamp)) &&
            ($timestamp <= PHP_INT_MAX) &&
            ($timestamp >= ~PHP_INT_MAX);
    }
}

if (!function_exists('is_timezone')) {
    /**
     * Check if a string is a valid timezone.
     *
     * @param string|null $timezone
     * @return bool
     */
    function is_timezone(?string $timezone) : bool
    {
        return in_array($timezone, timezone_identifiers_list());
    }
}

if (!function_exists('is_uuid')) {
    /**
     * Check if a string is a valid UUID.
     *
     * @param string|null $uuid
     * @return bool
     */
    function is_uuid(?string $uuid) : bool
    {
        return Str::isUuid($uuid);
    }
}


/*
|--------------------------------------------------------------------------
| Network Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('is_private_ip')) {
    /**
     * @param string $ip
     * @return bool
     */
    function is_private_ip(string $ip)
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}

if (!function_exists('get_domain')) {
    /**
     * Get domain by address.
     *
     * @param string|null $address
     * @return mixed
     */
    function get_domain(?string $address)
    {
        $parseUrl = parse_url(trim($address));

        if (empty($parseUrl['host'])) {
            $domain = explode('/', $parseUrl['path'], 2);

            return array_shift($domain);
        }

        return trim($parseUrl['host']);
    }
}

if (!function_exists('get_domain_ip')) {
    /**
     * Get IP by address.
     *
     * @param string|null $address
     * @param bool        $multiple
     * @return mixed
     */
    function get_domain_ip(?string $address, bool $multiple = false)
    {
        $domain = get_domain($address);

        if ($domain) {
            if ($multiple) {
                return gethostbynamel($domain);
            }

            $result = gethostbyname($domain);

            return filter_var($result, FILTER_VALIDATE_IP);
        }

        return false;
    }
}

if (!function_exists('get_url_path')) {
    /**
     * Get path by given URL.
     *
     * @param string|null $url
     * @return mixed
     */
    function get_url_path(?string $url)
    {
        $parseUrl = parse_url(trim($url));

        if (empty($parseUrl['host']) && !empty($parseUrl['path'])) {
            $path = explode('/', $parseUrl['path']);

            array_shift($path);

            return sprintf("/%s", implode('/', $path));
        }

        return !empty($parseUrl['path']) ? trim($parseUrl['path']) : null;
    }
}

if (!function_exists('domain_has_ssl')) {
    /**
     * Check if domain has SSL.
     *
     * @param string|null $url
     * @return bool
     */
    function domain_has_ssl(?string $url) : bool
    {
        try {
            $stream = stream_context_create([
                'ssl' => ['capture_peer_cert' => true]
            ]);

            $read    = fopen($url, 'rb', false, $stream);
            $context = stream_context_get_params($read);

            return !is_null($context['options']['ssl']['peer_certificate']);
        } catch (\Exception $exception) {
            return false;
        }
    }
}

if (!function_exists('is_external_url')) {
    /**
     * @param string $url
     * @return bool
     */
    function is_external_url(string $url) : bool
    {
        return get_domain(config('app.url')) !== get_domain($url);
    }
}

if (!function_exists('is_valid_cidr')) {
    /**
     * Validates the format of a CIDR notation string
     *
     * @param string $cidr
     * @return bool
     */
    function is_valid_cidr(string $cidr) : bool
    {
        $parts = explode('/', $cidr);

        if (count($parts) != 2) {
            return false;
        }

        $ip      = $parts[0];
        $netmask = intval($parts[1]);

        if ($netmask < 0) {
            return false;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $netmask <= 32;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $netmask <= 128;
        }

        return false;
    }
}

if (!function_exists('is_ip_cidr_match')) {
    /**
     * Check if an IP matching to a CIDR mask
     *
     * @param string $ip
     * @param string $range
     * @return bool
     */
    function is_ip_cidr_match(string $ip, string $range) : bool
    {
        return IpUtils::checkIp('185.168.68.96', '185.168.68.80/28');
    }
}

if (!function_exists('is_valid_ip')) {
    /**
     * Check if the given IP is valid.
     *
     * @param string $ip
     * @param array  $ips
     * @return bool
     */
    function is_valid_ip(string $ip, array $ips) : bool
    {
        $ips = array_filter($ips, function ($ip) {
            return filter_var($ip, FILTER_VALIDATE_IP);
        });

        return in_array($ip, $ips);
    }
}

if (!function_exists('is_valid_ip_range')) {
    /**
     * Check if the IP is in the given IP's range.
     *
     * @param string $ip
     * @param array  $ips
     * @return bool
     */
    function is_valid_ip_range(string $ip, array $ips) : bool
    {
        $ranges = array_filter($ips, function ($ip) {
            return is_valid_cidr($ip);
        });

        return IpUtils::checkIp($ip, $ranges);
    }
}


/*
|--------------------------------------------------------------------------
| Files && Directories Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('base64_encode_file')) {
    /**
     * Encoding file in base64 by given path.
     *
     * @param string $path
     * @param bool   $unlink
     * @return string|bool
     */
    function base64_encode_file(string $path, bool $unlink = false)
    {
        if (is_file($path)) {
            $content = file_get_contents($path);

            if ($unlink) {
                unlink($path);
            }

            return base64_encode($content);
        }

        return false;
    }
}

if (!function_exists('base64_encode_file_to_uri')) {
    /**
     * @param string $filePath .
     * @return string|bool
     */
    function base64_encode_file_to_uri(string $filePath)
    {
        $fileExtension = strtolower(substr(strrchr($filePath, '.'), 1));

        switch ($fileExtension) {
            case 'gif':
            case 'jpg':
            case 'png':
                $fileMimeType = 'image/' . $fileExtension;
                break;
            case 'ico':
                $fileMimeType = 'image/x-icon';
                break;
            case 'svg':
                $fileMimeType = 'image/svg+xml';
                break;
            case 'eot':
                $fileMimeType = 'application/vnd.ms-fontobject';
                break;
            case 'otf':
            case 'ttf':
            case 'woff':
            case 'woff2':
                $fileMimeType = 'application/octet-stream';
                break;
            default:
                return false;
        }

        $fileBase64 = base64_encode_file($filePath);

        return "data:{$fileMimeType};base64,{$fileBase64}";
    }
}

if (function_exists('delete_directory')) {
    /**
     * Recursive directory deletion
     *
     * @param string $directory
     * @return bool
     */
    function delete_directory(string $directory) : bool
    {
        if (!file_exists($directory)) {
            return true;
        }

        if (!is_dir($directory)) {
            return unlink($directory);
        }

        foreach (scandir($directory) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!delete_directory($directory . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($directory);
    }
}

if (!function_exists('clean_file_name')) {
    /**
     * @param string $fileName
     * @return string
     */
    function clean_file_name(string $fileName) : string
    {
        $fileExt     = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileNameStr = pathinfo($fileName, PATHINFO_FILENAME);

        // Replaces all spaces with hyphens.
        $fileNameStr = str_replace(' ', '-', $fileNameStr);
        // Removes special chars.
        $fileNameStr = preg_replace('/[^A-Za-z0-9\-\_]/', '', $fileNameStr);
        // Replaces multiple hyphens with single one.
        $fileNameStr = preg_replace('/-+/', '-', $fileNameStr);

        return $fileNameStr . '.' . $fileExt;
    }
}

if (!function_exists('upload_tmp_file')) {
    /**
     * @param mixed $file
     * @return string|bool
     */
    function upload_tmp_file($file) : bool|string
    {
        $storage = Storage::disk('local_tmp');

        if (!empty($file) && $file instanceof UploadedFile) {
            $filename = clean_file_name($file->getClientOriginalName());

            $filePath = $storage->putFileAs('/', $file, $filename);

            return $storage->path($filePath);
        }

        return false;
    }
}

if (!function_exists('delete_tmp_file')) {
    /**
     * @param string $path
     * @return bool
     */
    function delete_tmp_file(string $path) : bool
    {
        $storage = Storage::disk('local_tmp');

        $path = str_replace($storage->path(''), '', $path);

        return $storage->delete($path);
    }
}


/*
|--------------------------------------------------------------------------
| Logging Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('log_exception')) {
    /**
     * Add exception data to selected log driver
     *
     * @param \Throwable $exception
     * @param string     $channel
     * @return void
     */
    function log_exception(\Throwable $exception, string $channel)
    {
        $channels = config('logging.channels');

        if (!empty($channels[$channel]) && !empty($channels[$channel]['driver']) && $channels[$channel]['driver'] === 'single') {
            $msg = sprintf('%1$s: "%2$s" in %3$s:%4$d', get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine());

            Log::channel($channel)
                ->error($msg . PHP_EOL . $exception->getTraceAsString());
        }
    }
}

if (!function_exists('debug_log')) {
    /**
     * Add exception data to selected log driver
     *
     * @param mixed $data
     * @return void
     */
    function debug_log($data = null)
    {
        $backtrace = debug_backtrace(2, 1);
        $backtrace = array_shift($backtrace);

        $debugData = [
            'file' => $backtrace['file'] ?? '',
            'line' => $backtrace['line'] ?? 0,
            'data' => $data,
        ];

        Log::channel('debug')
            ->debug(var_export($debugData, true));
    }
}


/*
|--------------------------------------------------------------------------
| DateTime Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('round_to_nearest_minute_interval')) {
    /**
     * Round to nearest interval
     *
     * @param \DateTime|Carbon $dateTime
     * @param int              $minuteInterval
     * @return \DateTime|Carbon
     */
    function round_to_nearest_minute_interval(\DateTime|Carbon $dateTime, int $minuteInterval = 10) : \DateTime|Carbon
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            round($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
}

if (!function_exists('round_up_to_minute_interval')) {
    /**
     * Round to highest interval
     *
     * @param \DateTime|Carbon $dateTime
     * @param int              $minuteInterval
     * @return \DateTime|Carbon
     */
    function round_up_to_minute_interval(\DateTime|Carbon $dateTime, int $minuteInterval = 10) : \DateTime|Carbon
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
}

if (!function_exists('round_down_to_minute_interval')) {
    /**
     * Round to lowest interval
     *
     * @param \DateTime|Carbon $dateTime
     * @param int              $minuteInterval
     * @return \DateTime|Carbon
     */
    function round_down_to_minute_interval(\DateTime|Carbon $dateTime, int $minuteInterval = 10) : \DateTime|Carbon
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            floor($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
}


/*
|--------------------------------------------------------------------------
| Other Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('get_singular_class_name')) {
    /**
     * Return the Singular Capitalize Name.
     *
     * @param string $name
     * @return string
     */
    function get_singular_class_name(string $name) : string
    {
        return ucwords(Pluralizer::singular($name));
    }
}

if (!function_exists('is_base64')) {
    /**
     * Check if a string is base64 valid.
     *
     * @param string $string
     * @return bool
     */
    function is_base64(string $string) : bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string);
    }
}

if (!function_exists('timestamp_to_date')) {
    /**
     * @param int    $timestamp
     * @param string $format
     * @param string $timezone
     * @return mixed
     */
    function timestamp_to_date(int $timestamp, string $format = 'd.m.Y H:i', string $timezone = '') : ?string
    {
        try {
            $date = Carbon::createFromTimestamp($timestamp);

            if ($timezone) {
                $date->timezone($timezone);
            } else if ($timezone = request()->header('User-Timezone')) {
                $date->timezone($timezone);
            }

            return $date->format($format);
        } catch (\Exception $exception) {
            return false;
        }
    }
}

if (!function_exists('generate_strong_password')) {
    /**
     * Generate random strong password.
     *
     * @param int    $length
     * @param bool   $addDashes
     * @param string $availableSets
     * @return string
     */
    function generate_strong_password(int $length = 9, bool $addDashes = false, string $availableSets = 'luds') : string
    {
        $sets = [];

        if (strpos($availableSets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }

        if (strpos($availableSets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($availableSets, 'd') !== false) {
            $sets[] = '23456789';
        }

        if (strpos($availableSets, 's') !== false) {
            $sets[] = '!@%&*?'; // Forbidden symbols: #$
        }

        $all      = '';
        $password = '';

        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all      .= $set;
        }

        $all = str_split($all);

        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        $password = str_shuffle($password);

        if (!$addDashes) {
            return $password;
        }

        $dashLength = floor(sqrt($length));
        $dashStr    = '';

        while (strlen($password) > $dashLength) {
            $dashStr  .= substr($password, 0, $dashStr) . '-';
            $password = substr($password, $dashStr);
        }

        $dashStr .= $password;

        return $dashStr;
    }
}

if (!function_exists('add_session_error')) {
    /**
     * Add an error to Laravel session $errors
     *
     * @param string $key
     * @param string $errorMsg
     * @return void
     */
    function add_session_error(string $errorMsg, string $key = 'default')
    {
        $errors = Session::get('errors', new ViewErrorBag);

        if (!$errors instanceof ViewErrorBag) {
            $errors = new ViewErrorBag;
        }

        $bag = $errors->getBags()['default'] ?? new MessageBag;
        $bag->add($key, $errorMsg);

        Session::flash(
            'errors', $errors->put('default', $bag)
        );
    }
}

if (!function_exists('get_exception_data')) {
    /**
     * Get formatted exception details
     *
     * @param Throwable $exception
     * @return array
     */
    function get_exception_data(Throwable $exception) : array
    {
        return [
            'error' => utf8_encode($exception->getMessage()),
            'file'  => $exception->getFile(),
            'line'  => $exception->getLine(),
            'code'  => $exception->getCode(),
            'trace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }
}

if (!function_exists('convert_dollars_to_cents')) {
    /**
     * @param $value
     * @return int
     */
    function convert_dollars_to_cents($value) : int
    {
        $dollars = str_replace('$', '', $value);
        $dollars = (float) str_replace(',', '', $dollars);
        $cents   = bcmul($dollars, 100);

        return $cents;
    }
}

if (!function_exists('get_country_name_from_iso')) {
    /**
     * @param string $code
     * @return string
     */
    function get_country_name_from_iso(string $code) : string
    {
        try {
            $country = (new League\ISO3166\ISO3166)->alpha2($code);

            return $country['name'];
        } catch (\League\ISO3166\Exception\OutOfBoundsException $exception) {
            return '';
        }
    }
}

if (!function_exists('preg_replace_recursively')) {
    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @return string|string[]|null
     */
    function preg_replace_recursively(array|string $pattern, array|string $replacement, array|string $subject)
    {
        if (preg_match($pattern, $subject)) {
            $replacementChars = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

            $subject = preg_replace_callback($pattern, function ($matches) use ($replacement) {
                return $replacement;
            }, $subject);

            $subject = preg_replace_recursively($pattern, $replacement, $subject);
        }

        return $subject;
    }
}

if (!function_exists('str_replace_recursively_random')) {
    /**
     * @param string|string[] $pattern
     * @param string|string[] $subject
     * @return string|string[]|null
     */
    function str_replace_recursively_random(array|string $pattern, array|string $subject) : string
    {
        if (preg_match($pattern, $subject)) {
            $replacementArr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

            $subject = preg_replace_callback($pattern, function ($matches) use ($replacementArr) {
                return $replacementArr[array_rand($replacementArr)];
            }, $subject);

            $subject = str_replace_recursively_random($pattern, $subject);
        }

        return $subject;
    }
}

if (!function_exists('encode_data_with_crypto')) {
    /**
     * @param mixed  $data
     * @param string $key
     * @return mixed
     */
    function encode_data_with_crypto($data, string $key = '')
    {
        try {
            $data = is_array($data) ? json_encode($data) : $data;
            $key  = !empty($key) ? $key : substr(config('app.key'), 7);

            return \Defuse\Crypto\Crypto::encryptWithPassword(
                $data,
                base64_decode($key)
            );
        } catch (\Exception $exception) {
            return null;
        }
    }
}

if (!function_exists('decode_data_with_crypto')) {
    /**
     * @param string $encodedData
     * @param string $key
     * @return mixed
     */
    function decode_data_with_crypto(string $encodedData, string $key = '')
    {
        try {
            $key = !empty($key) ? $key : substr(config('app.key'), 7);

            return json_decode(
                \Defuse\Crypto\Crypto::decryptWithPassword(
                    $encodedData,
                    base64_decode($key)
                )
            );
        } catch (\Exception $exception) {
            return null;
        }
    }
}
