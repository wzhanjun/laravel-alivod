<?php

namespace Wzj\AliVod;

use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Exception\ServerException;
use Aliyun\Vod\Request\CreateUploadVideoRequest;
use Aliyun\Vod\Request\DeleteVideoRequest;
use Aliyun\Vod\Request\GetPlayInfoRequest;
use Aliyun\Vod\Request\GetVideoInfoRequest;
use Aliyun\Vod\Request\GetVideoPlayAuthRequest;
use Aliyun\Vod\Request\RefreshUploadVideoRequest;
use Wzj\AliVod\Exceptions\AliVodException;

class AliVod
{

    private $client;

    private $config;

    public function __construct(DefaultAcsClient $client, $config)
    {
        $this->client = $client;

        $this->config = $config;
    }


    /**
     * 获取视频上传地址和凭证
     *
     * @param $title
     * @param $filename
     * @param string $description
     * @param string $cover
     * @param $userData
     * @return mixed|\SimpleXMLElement
     * @throws AliVodException
     */
    public function createUploadVideo($title, $filename, $description = '', $cover = '', $userData = [])
    {
        $request = new CreateUploadVideoRequest();

        $request->setTitle($title);
        $request->setFileName($filename);
        $request->setDescription($description ?: $title);
        $request->setCoverURL($cover);

        if ($tags = array_get($this->config, 'video_tags')) {
            $request->setTags($tags);
        }

        if (!empty($userData))
        {
            $request->setUserData(json_encode($userData));
        }

        if ($cateId = array_get($this->config, 'upload_cate_id'))
        {
            $request->setCateId($cateId);
        }

        if ($templateGroupId = array_get($this->config, 'upload_template_group_id'))
        {
            $request->setCateId($templateGroupId);
        }

        try {
            $uploadInfo = $this->client->getAcsResponse($request);
        } catch (\Exception $e) {
            throw new AliVodException($e->getMessage());
        }

        return $uploadInfo;
    }


    /**
     * 刷新视频上传凭证
     *
     * @param $videoId
     * @throws AliVodException
     * @return string
     */
    public function refreshUploadVideo($videoId)
    {
        $request = new RefreshUploadVideoRequest();

        $request->setVideoId($videoId);

        try {
            $refreshInfo = $this->client->getAcsResponse($request);
        } catch (\Exception $e) {
            throw new AliVodException($e->getMessage());
        }

        return $refreshInfo;
    }

    /**
     * 获取视频播放地址
     *
     * @param $videoId
     * @param int $timeout
     * @return mixed|\SimpleXMLElement
     * @throws AliVodException
     */
    public function getPlayInfo($videoId, $timeout = 3600)
    {
        $request = new GetPlayInfoRequest();

        $request->setVideoId($videoId);
        $request->setAuthTimeout($timeout);

        try {
            $playInfo = $this->client->getAcsResponse($request);
        } catch (\Exception $e) {
            throw new AliVodException($e->getMessage());
        }

        return $playInfo;
    }


    /**
     * 播放凭证
     *
     * @param $videoId
     * @param int $timeout
     * @return mixed|\SimpleXMLElement
     * @throws AliVodException
     */
    public function getPlayAuth($videoId, $timeout = 1800)
    {
        $request = new GetVideoPlayAuthRequest();

        $request->setVideoId($videoId);
        $request->setAuthInfoTimeout($timeout);

        try {
            $playAuth = $this->client->getAcsResponse($request);
        } catch (\Exception $e) {
            throw new AliVodException($e->getMessage());
        }

        return $playAuth;
    }


    /**
     * 获取视频信息
     *
     * @param $videoId
     * @return mixed|\SimpleXMLElement
     * @throws AliVodException
     */
    public function getVideoInfo($videoId)
    {

        $request = new GetVideoInfoRequest();

        $request->setVideoId($videoId);

        try {
            $videoInfo  = $this->client->getAcsResponse($request);
        } catch (\Exception $e) {
            throw new AliVodException($e->getMessage());
        }

        return $videoInfo;

    }


    /**
     * 删除视频
     *
     * @param $videoIds
     * @return mixed|\SimpleXMLElement
     * @throws AliVodException
     */
    public function deleteVideo($videoIds)
    {
        $request = new DeleteVideoRequest();

        $request->setVideoIds($videoIds);

        try {
            $delInfo = $this->client->getAcsResponse($request);
        } catch (\Exception $e) {
            throw new AliVodException($e->getMessage());
        }

        return $delInfo;
    }

}