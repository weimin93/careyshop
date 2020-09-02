<?php
/**
 * @copyright   Copyright (c) http://careyshop.cn All rights reserved.
 *
 * CareyShop    OSS基类
 *
 * @author      zxm <252404501@qq.com>
 * @date        2020/7/23
 */

namespace oss;

use think\Request;

abstract class Upload
{
    /**
     * 错误信息
     * @var string
     */
    protected $error = '';

    /**
     * @var Request Request 实例
     */
    protected $request;

    /**
     * 待删除资源列表
     * @var array
     */
    protected $delFileList = [];

    /**
     * 待删除资源Id列表
     * @var array
     */
    protected $delFileIdList = [];

    /**
     * 资源替换
     * @var string
     */
    protected $replace = '';

    /**
     * 构造函数
     * @access public
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->initialize();
    }

    /**
     * 初始化
     * @access public
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * 返回错误信息
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置错误信息
     * @access public
     * @param string $error 错误信息
     * @return false
     */
    public function setError(string $error)
    {
        $this->error = $error;
        return false;
    }

    /**
     * 添加待删除资源
     * @access public
     * @param string $path 资源路径
     * @return void
     */
    public function addDelFile(string $path)
    {
        $this->delFileList[] = $path;
    }

    /**
     * 添加待删除资源Id
     * @access public
     * @param mixed $id 资源Id
     * @return void
     */
    public function addDelFileId($id)
    {
        $this->delFileIdList[] = $id;
    }

    /**
     * 获取待删除资源Id列表
     * @access public
     * @return array
     */
    public function getDelFileIdList()
    {
        return $this->delFileIdList;
    }

    /**
     * 查询条件数据转字符
     * @access public
     * @param array $options 查询条件
     * @return string
     */
    protected function queryToString($options = [])
    {
        $temp = [];
        foreach ($options as $key => $value) {
            if (is_string($key) && !is_array($value)) {
                $temp[] = rawurlencode($key) . '=' . rawurlencode($value);
            }
        }

        return implode('&', $temp);
    }

    /**
     * 根据文件mime判断资源类型 1=普通资源 3=视频资源
     * @access public
     * @param $mime
     * @return string
     */
    protected function getFileType($mime)
    {
        if (stripos($mime, 'video') !== false) {
            return 3;
        }

        return 1;
    }

    /**
     * 获取上传地址
     * @access protected
     * @return array
     */
    abstract protected function getUploadUrl();

    /**
     * 获取上传Token
     * @access protected
     * @param string $replace 替换资源(path)
     * @return array
     */
    abstract protected function getToken($replace = '');

    /**
     * 接收第三方推送数据
     * @access protected
     * @return array
     */
    abstract protected function putUploadData();

    /**
     * 上传资源
     * @access protected
     * @return array
     */
    abstract protected function uploadFiles();

    /**
     * 获取资源缩略图实际路径
     * @access protected
     * @param array $urlArray  路径结构
     * @param array $styleList 样式集合
     * @return void
     */
    abstract protected function getThumbUrl(array $urlArray, array $styleList);

    /**
     * 批量删除资源
     * @access protected
     * @return bool
     */
    abstract protected function delFileList();

    /**
     * 批量删除资源
     * @access protected
     * @param string $path 路径
     * @return void
     */
    abstract protected function clearThumb(string $path);

    /**
     * 响应实际下载路径
     * @access protected
     * @param string $url      路径
     * @param string $filename 文件名
     * @return void
     */
    abstract protected function getDownload(string $url, string $filename);

    /**
     * 获取资源缩略图信息
     * @access protected
     * @param string $url 路径
     * @return array
     */
    abstract protected function getThumbInfo(string $url);
}
