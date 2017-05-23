<?php
namespace Hexor\WXPic;

use Hexor\WXPic\Exceptions\WXPicFileException;

class WXPic
{
    public function donow()
    {
        echo "Do it now or never";
    }

    public function picCacheDirectory()
    {
        return env('WXPIC_CACHE_DIRECTORY', '/wechat_cache_image/');
    }

    public function wx2Local($wechatUrl, $isFullPath = true)
    {
        $record = WXCacheImg::where('wechat_url_md5', md5($wechatUrl))->first();

        if (!empty($record) && file_exists($record['local_path'])) {
            if ($isFullPath == false) {
                return $record['local_path'];
            }
            return $record['local_full_url'];
        }


        list($fullPath, $partPath) = $this->cacheWechatImage($wechatUrl);

        if (!empty($fullPath) && !empty($partPath)) {
            if ($isFullPath == false) {
                return $partPath;
            }
            return $fullPath;
        }
    }

    public function cacheWechatImage($imageUrl)
    {
        $rNum = rand(2, 9999);
        if ($rNum < 10) {
            $rNum = "000" . $rNum;
        } elseif ($rNum < 100) {
            $rNum = "00" . $rNum;
        } elseif ($rNum < 1000) {
            $rNum = "0" . $rNum;
        }

        $filename = time(). $rNum;

        $mimes = array(
            'image/bmp' => 'bmp',
            'image/gif' => 'gif',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/x-icon' => 'ico'
        );

        $resultUrl = null;

        // 获取响应头
        try {
            if (($headers = get_headers($imageUrl, 1)) !== false) {
                // 获取响应的类型
                $type = $headers['Content-Type'];
                // 如果符合我们要的类型
                if (isset($mimes[$type])) {
                    $extension = $mimes[$type];
                    $file_path = public_path().$this->picCacheDirectory().$filename.".".$extension;
                    // 获取数据并保存
                    $contents = file_get_contents($imageUrl);
                    if (file_put_contents($file_path, $contents)) {
                        $resultUrl = env('APP_URL').$this->picCacheDirectory().$filename.".".$extension;
                    }

                    $localServerImgFullURL = $resultUrl;
                    $wechatServerImgFullURL = $imageUrl;

                    WXCacheImg::where('local_url_md5', md5($localServerImgFullURL))->delete();
                    WXCacheImg::where('wechat_url_md5', md5($wechatServerImgFullURL))->delete();

                    WXCacheImg::create([
                        'local_url_md5' => md5($localServerImgFullURL),
                        'wechat_url_md5' => md5($wechatServerImgFullURL),
                        'local_path' => $file_path,
                        'local_full_url' => $localServerImgFullURL,
                        'wechat_full_url' => $wechatServerImgFullURL
                    ]);

                    return array(
                        $resultUrl,
                        $file_path
                    );
                }
            }
        } catch (Exception $e) {
            throw new WXPicFileException($e);
            Log::warning("write cache image failed");
        }
    }

    public function local2WX($localUrl)
    {
        $record = WXCacheImg::where('local_url_md5', md5($localUrl))->first();

        if (empty($record)) {
            $record = WXCacheImg::where('local_path', $localUrl)->first();
        }

        if (!empty($record) && isset($record['wechat_full_url'])) {
            return $record['wechat_full_url'];
        }
    }

    public function clearCachedImages($cacheMinutes = 1)
    {
        try {
            $files = File::allFiles(public_path().$this->picCacheDirectory());

            foreach ($files as $originFile) {
                $file = basename($originFile, ".png");
                Log::info($file);
                $cacheSeconds = $cacheMinutes * 60 + 1;

                $lastCacheTS = (time() - $cacheSeconds) * 10000;

                if ($file < $lastCacheTS) {
                    File::delete($originFile);
                }
            }
        } catch (Exception $e) {
            throw new WXPicFileException($e);
            Log::warning("delete cache image failed");
        }
    }
}
