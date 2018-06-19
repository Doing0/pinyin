### 说明：

> 1. 我是在thinkphp5的开发环境书写,所以本文针对此框架。如果是其他框架自行修改（原理很简单）
> 2. 汉字转全拼和首字母，支持带声调和保留非字母内容(thinkphp的extend扩展包)

### 安装

[下载地址（点击）](https://github.com/Doing0/pinyin)

不管用git还是下载zip最终目的是得到以下目录结构，并把文件汉字框架的application/extend/pinyin（如下图）

![安装.png](https://upload-images.jianshu.io/upload_images/8189586-75dbf86e9bd5d7bc.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)


### 使用 在方法体内调用即可

**温馨提示：在调用方法时，类的顶部一定要先引用命名空间
 use pinyin\ChinesePinyin**;

~~~php+HTML
     /** 
     *中文转拼音测试
     */
    public function index()
    {
        echo (ChinesePinyin::getInstance())->HaveTone('带声调的汉语拼音');
        echo '----------HaveTone();带声调的汉语拼音' . ".<br>";
        echo (ChinesePinyin::getInstance())->notHaveTone('无声调的汉语拼音');
        echo '----------notHaveTone();无声调的汉语拼音' . ".<br>";
        echo (ChinesePinyin::getInstance())->fistPinYinToUc('首字母大写只包括汉字BuHanPinYin');
        echo '----------fistPinYinToUc();首字母大写只包括汉字BuHanPinYin' . ".<br>";
        echo (ChinesePinyin::getInstance())->onlyFistPinYinToUc('首字母拼音大写缩写BuHanPinYin');
        echo '-----------onlyFistPinYinToUc();首字母拼音大写缩写BuHanPinYin' . ".<br>";
    }//pub
~~~



### 结果输出

![结果.png](https://upload-images.jianshu.io/upload_images/8189586-b21a75ed5dfecc2c.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)


本类相对很简单，在这里不做过多的累赘，如果有不清楚的看下ChinesePinyin.php源码就很明白了
