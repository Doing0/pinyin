<?php
/**
 * Created by PhpStorm
 * PROJECT: tp_grow
 * User: Doing<vip.dulin@gmial.com>
 * Date: 018/6/19/09:58
 * Desc:汉语转拼音extend包
 */

namespace pinyin;
//拼音语言包地址
define('PINYIN_ROOT', dirname(__FILE__));

class ChinesePinyin {

    //utf-8中国汉字集合
    private $ChineseCharacters;
    //编码
    private $charset = 'utf-8';
    //实例化对象
    private static $obj;
    //拼音声调
    private $tone = ['ā', 'á', 'ǎ', 'à', 'ō', 'ó', 'ǒ', 'ò', 'ē', 'é', 'ě', 'è',
        'ī', 'í', 'ǐ', 'ì', 'ū', 'ú', 'ǔ', 'ù', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü'];
    //拼音无声调
    private $notTone = ['a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'e', 'e',
        'e', 'e', 'i', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'v', 'v', 'v', 'v', 'v'];
    //空格
    const SPACE = ' ';
    //忽略非汉字
    const IGNORE_NON_CHINESE = true;
    //不忽略非汉字
    const NOT_IGNORE_NON_CHINESE = false;

    /**构造方法加载拼音语言包
     * ChinesePinyin constructor.g
     */
    private function __construct()
    {
        //读取utf-8中国汉字集合
        if (empty($this->ChineseCharacters))
        {
            $this->ChineseCharacters = file_get_contents(PINYIN_ROOT . '/ChineseCharacters.dat');
        }
    }//pub

    /** 简述:单例模式获取对象
     */
    public static function getInstance()
    {
        if (is_null(self::$obj))
        {
            self::$obj = new self();
        }
        return self::$obj;
    }//pf

    /*
    * 转成带有声调的汉语拼音
    * param $input_char String  需要转换的汉字
     * param $ignore_non_chinese  Boolean     是否忽略 非汉字内容
     * 当$ignore_non_chinese = false时(我love你) 转换结果是wǒ love nǐ非中文部分原样输出
     * 当$ignore_non_chinese = true时(我love你) 转换结果是wǒ nǐ非中文部分忽略
     * return $str
    */
    public function haveTone($input_char, $ignore_non_chinese = self::NOT_IGNORE_NON_CHINESE)
    {
        //获取字符串长度
        $wordLen = $this->getWordLen($input_char);
        //定义返回字符串变量
        $output_char = '';
        for ($i = 0; $i < $wordLen; $i++)
        {
            //截取一个字符串汉字中文乱码mb版
            $word = $this->subOneWordByMb($input_char, $i);
            $matches = $this->getChinesePinYinHaveTone($word);
            if ($matches)
            {
                $output_char .= self::SPACE . $matches . self::SPACE;
            }else if ($ignore_non_chinese == self::NOT_IGNORE_NON_CHINESE)
            {
                $output_char .= $word;
            }
        }//for
        return $output_char;
    }//pub

    /*
    * 转成带无声调的汉语拼音
    * param $input_char String  需要转换的汉字
    * 当$ignore_non_chinese = false时(我love你) 转换结果是wo love ni非中文部分原样输出
     * 当$ignore_non_chinese = true时(我love你) 转换结果是wo ni非中文部分忽略
     * return $str
    */
    public function notHaveTone($input_char, $ignore_non_chinese = self::NOT_IGNORE_NON_CHINESE)
    {
        $notTone = $this->removeTone($this->haveTone($input_char, $ignore_non_chinese));
        return $notTone;
    }//pub

    /*
    * 汉语 首字母转拼音且大写(只包括汉字)
    * param $input_char String  需要转换的汉字
     * return $str
    */
    public function fistPinYinToUc($input_char)
    {
        $notHaveTone = $this->notHaveTone($input_char, self::IGNORE_NON_CHINESE);
        //首字母大写处理 Wo Shi Xiao Ming(我是小明)
        $fistPinYinToUc = ucwords($notHaveTone);
        return $fistPinYinToUc;
    }//pub

    /*
   * 汉语 首字母拼音大写缩写(只包括汉字)
   * param $input_char String  需要转换的汉字
    * return $str
   */
    public function onlyFistPinYinToUc($input_char)
    {
        // WSXM(我是小明)
        return preg_replace('/[^A-Z]/', '', $this->fistPinYinToUc($input_char));
    }//pub


    /**获取要转换的字符长度
     *
     * @param $input_char
     *
     * @return int
     */
    private function getWordLen($input_char)
    {
        return mb_strlen($input_char, $this->charset);
    }

    /**获取中文部分的拼音带声调(只转换中文部分)
     *
     * @param $word [需要检查的字符]
     *
     * @return bool
     */
    private function getChinesePinYinHaveTone($word)
    {
        $checkZh = preg_match('/^[\x{4e00}-\x{9fa5}]$/u', $word);
        $checkZhPreg = preg_match('/\,' . preg_quote($word) . '(.*?)\,/', $this->ChineseCharacters, $matches);
        //$matche
        //[0] => string(8) ",我wǒ,"
        //[1] => string(3) "wǒ"
        if ($checkZh && $checkZhPreg) return $matches[1];
        return false;

    }

    /**截取一个字符串防止中文乱码mb版
     *
     * @param $input_char
     * @param $i [循环变量]
     *
     * @return string
     */
    private function subOneWordByMb($input_char, $i)
    {
        return mb_substr($input_char, $i, 1, $this->charset);
    }

    /**把声调去掉,变成无声调的拼音
     *
     * @param $have_tone_pinyin [有声调的拼音]
     *
     * @return $str无声调的字符串
     */
    private function removeTone($have_tone_pinyin)
    {
        return str_replace($this->tone, $this->notTone, $have_tone_pinyin);
    }//pub


}//class