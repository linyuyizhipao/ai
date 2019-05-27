<?php
namespace Lingyuyizhipao;
/**
 * dosc https://ai.baidu.com/docs/#/ASR-Online-PHP-SDK/top
 * @property Voice static $_instance
 * @property AipSpeech $Ai
 */

class Voice
{
    // 你的 APPID AK SK
    const APP_ID = '16334530';
    const API_KEY = 'VAG19BcZHkwpeLFSo2DOsAgG';
    const SECRET_KEY = 'vKil1zFaiHRLPWHxzwdPaBOgSz5zIHPE';
    const RATE_VAL = 8000; //采样率，16000，固定值。但是我必须设置未8000才能正常解析我们的音频
    const DEV_PID = 1537; //不填写lan参数生效，都不填写，默认1537（普通话 输入法模型），dev_pid参数见本节开头的表格
    const DEFAULT_VOICE_EXT = 'amr'; //默认的音频文件


    private static $_instance;
    private $Ai;
    private $error = [];
    private function __construct()
    {
    }
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
            self::$_instance->Ai = new AipSpeech(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        }
        return self::$_instance;
    }

    /**
     * array(5) {
    ["corpus_no"]=>
    string(19) "6695538279964801191"
    ["err_msg"]=>
    string(8) "success."
    ["err_no"]=>
    int(0)
    ["result"]=>
    array(1) {
    [0]=>
    string(236) "来一个很长的语音，一分钟十分钟20分钟30分钟40分钟50分钟60分钟全身哦80分钟哦，十分钟的法轮功电话一存哦你玩20分钟，130分钟130分钟一毛五，成交一玩了一年多你来你和段段。"
    }
    ["sn"]=>
    string(21) "581415061491558926487"
    }
     *
     *
     * amr转换成文字
    * @param  string $filePath 音频文件路径，可以是http的地址，也可以是绝对路径
    */
    public function amrVoiceTranslate($filePath,$ext = self::DEFAULT_VOICE_EXT)
    {
        $client = $this->Ai;
        $e = @file_get_contents($filePath);
        if(empty($e))
           return false;

        $res = $client->asr($e, $ext, self::RATE_VAL, array(
            'dev_pid' => self::DEV_PID,
        ));
        if(!empty($res['err_no'])){
            $this->error[] = $res['err_msg']??"api异常";
            return false;
        }
        if(!isset($res['result'],$res['result'][0])){
            $this->error[] = $res['err_msg']??"api异常";
        }
        //翻译成功
        return $res['result'][0];

    }



    public function getError()
    {
        return $this->error;
    }
    public function getFirstError()
    {
        return $this->error[0]??[];
    }
}


