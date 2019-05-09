<?php
namespace app\common\library\storage\engine;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-05 13:52
 */

class Aliyun extends Server
{
    private $config;

    /**
     * 构造方法
     * Qiniu constructor.
     * @param $config
     * @throws \think\Exception
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
    }
    /**
     * 执行上传
     * @return bool|mixed
     * @throws \Exception
     */
    public function upload()
    {
        // 要上传图片的本地路径
        $realPath = $this->file->getRealPath();
        //exit($realPath);

        // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
        $accessKeyId = $this->config['access_key'];
        $accessKeySecret = $this->config['secret_key'];
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = $this->config['endpoint'];
        // 存储空间名称
        $bucket= $this->config['bucket'];
        // 文件名称
        $object = $this->fileName;
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket, $object, $realPath);
        } catch(OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * 返回文件路径
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}