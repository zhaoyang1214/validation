# 这是一个验证类

## 安装
#### 使用composer安装
```
composer require snow/validation
```

## 一、使用
1、简单使用
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add('username', new Alnum());

$messages = $validation->validate([
    'username' => 'saas2;:sssaa'
]);

if (count($messages)) {
    foreach ($messages as $msg) {
        echo $msg, PHP_EOL;// username必须只包含字母和数字
        // echo $msg->getMessage(); // username必须只包含字母和数字
    }
}
```

**最新支持多维数组验证**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add('statistics.send.total_clicks', new Alnum([
    Alnum::OPT_MESSAGE => '统计点击数必须只包含字母和数字'
]));

/*$validation->add([
    'statistics.send.total_clicks',
    'statistics.send.total_exposure',
], new Alnum([
    Alnum::OPT_MESSAGE => [
        'statistics.send.total_clicks' => '统计点击数必须只包含字母和数字',
        'statistics.send.total_exposure' => '统计曝光必须只包含字母和数字',
    ]
]));*/

$messages = $validation->validate([
    'statistics' => [
        'send' => [
            'total_clicks' => 'aaa--',
            'total_exposure' => 'bbb'
        ]
    ]
]);

if (count($messages)) {
    foreach ($messages as $msg) {
         echo $msg->getMessage(); // 统计点击数必须只包含字母和数字
    }
}
```

2、定制提示信息
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add('username', new Alnum([
    'message' => '字段:field必须只包含字母和数字'
]));

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);
foreach ($group as $msg) {
    echo $msg; // 字段username必须只包含字母和数字
}
```

3、给字段设置标签
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add('username', new Alnum([
    'message' => ':field必须只包含字母和数字',
    'label' => '用户名',
]));

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL; // 用户名必须只包含字母和数字
}
```

4、给多个字段批量设置验证规则
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add([
    'username',
    'name',
], new Alnum([
    'message' => [
        'username' => '字段1:field必须只包含字母和数字',
        'name' => '字段2:field必须只包含字母和数字',
    ]
]));

$group = $validation->validate([
    'username' => 'saas2;:s--',
    'name' => 'test--',
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL;
    // 字段1username必须只包含字母和数字
    // 字段2name必须只包含字母和数字
}
```

5、设置允许字段为空值
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add([
    'username',
    'name',
], new Alnum([
    'message' => [
        'username' => '字段1:field必须只包含字母和数字',
        'name' => '字段2:field必须只包含字母和数字',
    ],
    'allowEmpty' => true,
]));

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL; // 字段1username必须只包含字母和数字
}



// 亦可指定空值
$validation = new Validation();
$validation->add([
    'username',
    'name',
], new Alnum([
    'message' => [
        'username' => '字段1:field必须只包含字母和数字',
        'name' => '字段2:field必须只包含字母和数字',
    ],
    // 指定空值
    'allowEmpty' => [0, false],
]));

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL;
    // 字段1username必须只包含字母和数字
    // 字段2name必须只包含字母和数字
}
```

6、验证失败后取消验证（不再继续验证）
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add([
    'username',
    'name',
], new Alnum([
    'message' => [
        'username' => '字段1:field必须只包含字母和数字',
        'name' => '字段2:field必须只包含字母和数字',
    ],
    'cancelOnFail' => true
]));

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL;
    // 字段1username必须只包含字母和数字
}
```

7、给字段批量设置验证器
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;
use Snow\Validation\Validation\Validator\Alpha;

$validation = new Validation();
$validation->rules('username', [
    new Alnum(),
    new Alpha(),
]);

$group = $validation->validate(['username' => 'saas2;:s--']);

foreach ($group as $msg) {
    echo $msg, PHP_EOL;
    // username必须只包含字母和数字
    // username 必须只包含字母
}
```

8、给多个字段批量设置验证器，并根据字段名过滤消息
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;
use Snow\Validation\Validation\Validator\Alpha;

$validation = new Validation();
// 设置默认提示
$validation->setDefaultMessages([
    Alnum::TYPE => ':field 只能为字母与数字',
    Alpha::TYPE => ':field 只能为字母',
]);
// 生成四条验证
$validation->rules([
    'username',
    'name',
], [
    new Alnum([
        'message' => [
            'username' => '用户名必须只包含字母和数字（我的优先级比较高）',
            'name' => '姓名必须只包含字母和数字（我的优先级比较高）',
        ]
    ]),
    new Alpha(),
]);

// 也可以这么写
/*$validation->rules([
    'username',
    'name',
], [
    new Alnum([
        'message' => ':field必须只包含字母和数字（我的优先级比较高）'
    ]),
    new Alpha(),
]);*/

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL;
    // 用户名必须只包含字母和数字（我的优先级比较高）
    // 姓名必须只包含字母和数字（我的优先级比较高）
    // username 只能为字母
    // name 只能为字母
}

// 根据字段过滤消息
$messages = $group->filter('name');
foreach ($messages as $msg) {
    echo $msg, PHP_EOL;
}
```

## 二、定制验证类
通过继承`Snow\Validation\Validation`类可创建一些验证类重复使用，类支持三个事件`initialize`、`beforeValidate`、`afterValidate`，分别是初始化、
验证之前、验证之后触发。需要注意的是当`beforeValidate`返回`false`时将不会验证数据

例如用户注册时需要验证，可以实现一个用户验证类
```php
namespace Test;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;
use Snow\Validation\Validation\Validator\Alpha;
use Snow\Validation\Validation\Message;

class UserValidation extends Validation
{
    /**
     * 实例化时调用
     */
    public function initialize()
    {
//        $this->setDefaultMessages([
//            Alnum::TYPE => ':field 只能为字母与数字',
//            Alpha::TYPE => ':field 只能为字母',
//        ]);
        // 添加验证规则
        $this->add('username', new Alnum())
            ->add('name', new Alpha());
    }

    /**
     * 功能：验证之前调用，失败则取消验证
     * 修改日期：2019/8/13
     *
     * @param array|object $data 待验证数据
     * @param \Snow\Validation\Validation\Group $messages 验证消息
     * @return bool
     */
    public function beforeValidate($data, $messages)
    {
        if ($data['username'] == 'admin') {
            $messages->appendMessage(
                new Message('不允许注册admin')
            );
            return false;
        }
        return true;
    }

    /**
     * 功能：验证之后调用
     * 修改日期：2019/8/13
     *
     * @param array|object $data 待验证数据
     * @param \Snow\Validation\Validation\Group $messages 验证消息
     * @return void|mixed
     */
    public function afterValidate($data, $messages)
    {
    }
}


// 使用
$userValidation = new UserValidation();
$group = $userValidation->validate([
    'username' => 'admin'
]);

// beforeValidate返回false时validate才会返回false,其他场景下均返回\Snow\Validation\Validation\Group
// 如果没有自定义beforeValidate方法，或beforeValidate方法返回非false,可直接用count($group)来判断验证是否通过
if ($group === false) {
    $group = $userValidation->getMessages();
}
foreach ($group as $msg) {
    echo $msg; // 不允许注册admin
}

```
如果上面`UserValidation`类没有自定义`beforeValidate`方法,则可以直接使用下面写法判断是否验证成功：
```php
if (count($group)) {
    // 失败
    echo $group[0]->getMessage(); // 第一条错误消息
}
```

## 三、所有验证器
| 验证器 | 功能 | 是否是多字段 | 是否已实现 |
| --- |  --- | :---: | :---: |
| \Snow\Validation\Validation\Validator\Alnum | 检查字母数字字符 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Alpha | 检查字母字符 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Between | 验证值位于两个值之间<br>对于值x，如果minimum<=x<=maximum，则通过 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Callback | 调用用户函数进行验证 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Confirmation | 检查两个值是否相同 | 否 | 是 |
| \Snow\Validation\Validation\Validator\CreditCard | 检查信用卡号码是否有效 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Date | 检查值是否为有效日期 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Digit | 检查数字字符（纯数字） | 否 | 是 |
| \Snow\Validation\Validation\Validator\Email | 检查值是否具有正确的e-mail格式 | 否 | 是 |
| \Snow\Validation\Validation\Validator\ExclusionIn | 检查值是否不包含在值列表中 | 否 | 是 |
| \Snow\Validation\Validation\Validator\File | 检查值是否具有正确的文件<br>包括不限于文件大小、文件类型等 | 否 | 是 |
| \Snow\Validation\Validation\Validator\IDCard | 检查18位身份证号码是否正确 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Identical | 检查值是否与其他值相同 | 否 | 是 |
| \Snow\Validation\Validation\Validator\InclusionIn | 检查值是否包含在值列表中 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Numericality | 检查是否是有效的数值 | 否 | 是 |
| \Snow\Validation\Validation\Validator\PresenceOf | 验证字段的值不是null、空字符串或空数组 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Regex | 检查值是否匹配正则表达式 | 否 | 是 |
| \Snow\Validation\Validation\Validator\StringLength | 验证字符串的长度范围 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Uniqueness | 验证字段的值是否唯一 | 是 | 是 |
| \Snow\Validation\Validation\Validator\Url | 检查值是否具有url格式 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Phone | 检查值是否具有手机号码格式 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Json | 检查值是否具有Json格式 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Base64 | 检查值是否具有Base64格式 | 否 | 是 |
| \Snow\Validation\Validation\Validator\Ip | 检查值是否是合法Ip | 否 | 是 |


## 四、选项参数
##### 1、公共选项参数
| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_MESSAGE | message | - | 提示信息 | 否 |
| OPT_LABEL | label | 字段名 | 字段标签 | 否 |
| OPT_CODE | code | 0 | 消息码 | 否 |
| OPT_ALLOW_EMPTY | allowEmpty | false | 是否允许为空 | 否 |
| OPT_CANCEL_ON_FAIL | cancelOnFail | false | 验证失败后是否继续验证 | 否 |

##### 2、其他特殊验证器选项参数
- 验证器`\Snow\Validation\Validation\Validator\Between`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_MINIMUM | minimum | - | 最小值 | 是 |
| OPT_MAXIMUM | maximum | - | 最大值 | 是 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Between;

$validation = new Validation();
$validation->add('age', new Between([
    Between::OPT_MAXIMUM => 90,
    Between::OPT_MINIMUM => 10,
    Between::OPT_MESSAGE => ':field 必须在:minimum到:maximum之间', // 非必须
]));

$group = $validation->validate([
    'age' => 9
]);
foreach ($group as $msg) {
    echo $msg->getMessage(), PHP_EOL;
}
```

- 验证器`\Snow\Validation\Validation\Validator\Callback`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_CALLBACK | callback | - | 回调函数 | 是 |
**使用（支持call_user_func函数所有用法）：**

**用法1:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Callback;
use Snow\Validation\Validation\Validator\Alnum;

$validation = new Validation();
$validation->add('username', new Callback([
    Callback::OPT_CALLBACK => function ($data) {
        if ($data['username'] == 'admin') {
            return false;
        }
        return new Alnum();
    }
]));

$group = $validation->validate([
    'username' => 'saas2;:s--'
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL; // username必须只包含字母和数字
}
```

**用法2:**
```php
// 定义验证函数
function checkUsername($data)
{
    return $data['username'] == 'admin' ? false : true;
}

// 使用
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Callback;

$validation = new Validation();
$validation->add('username', new Callback([
    Callback::OPT_CALLBACK => 'checkUsername'
]));

$group = $validation->validate([
    'username' => 'admin'
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL; // username验证失败
}
```

- 验证器`\Snow\Validation\Validation\Validator\Confirmation`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_WITH | with | - | 要比较的字段名 | 是 |
| OPT_LABEL_WITH | labelWith | 选项with的值 | 要比较的字段的标签 | 否 |
| OPT_IGNORE_CASE | ignoreCase | false | 忽略大小写 | 否 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Confirmation;

$validation = new Validation();
$validation->add('password', new Confirmation([
    Confirmation::OPT_WITH => 'confirmPassword',
    Confirmation::OPT_LABEL => '密码',
    Confirmation::OPT_LABEL_WITH => '确认密码',
]));

$group = $validation->validate([
    'password' => 'admin123',
    'confirmPassword' => 'Admin123',
]);
foreach ($group as $msg) {
    echo $msg, PHP_EOL; // 密码与确认密码必须相同
}
```

- 验证器`\Snow\Validation\Validation\Validator\Date`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_FORMAT | format | Y-m-d | 日期格式 | 否 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Date;

$validation = new Validation();
$validation->add('strat_time', new Date([
    Date::OPT_FORMAT => 'Y-m-d H:i:s'
]));

$group = $validation->validate([
    'strat_time' => '2019-12-30'
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // strat_time不是有效的日期
}
```

- 验证器`\Snow\Validation\Validation\Validator\ExclusionIn`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_DOMAIN | domain | - | 值范围 | 是 |
| OPT_STRICT | strict | false | 是否严格校验 | 否 |

**用法1:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\ExclusionIn;

$validation = new Validation();
$validation->add(['hobbies', 'status'], new ExclusionIn([
    ExclusionIn::OPT_DOMAIN => [
        'hobbies' => ['足球', '篮球', '羽毛球', '乒乓球'],
        'status' => [0, 1, 2],
    ]
]));

$group = $validation->validate([
    'hobbies' => '乒乓球',
    'status' => '0'
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // hobbies必须不能在足球,篮球,羽毛球,乒乓球范围内
                        // status必须不能在0,1,2范围内
}
```

**用法2:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\ExclusionIn;

$validation = new Validation();
$validation->add(['status', 'type'], new ExclusionIn([
    ExclusionIn::OPT_DOMAIN => [0, 1, 2],
    ExclusionIn::OPT_STRICT => true,
]));

$group = $validation->validate([
    'status' => '0',
    'type' => 0,
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // type必须不能在0,1,2范围内
}
```

- 验证器`\Snow\Validation\Validation\Validator\Identical`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_ACCEPTED | accepted | - | 服务条款 | accepted与value至少设置一个 |
| OPT_VALUE | value | - | 比较值 | accepted与value至少设置一个 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Identical;

$validation = new Validation();
$validation->add('terms', new Identical([
    Identical::OPT_ACCEPTED => 'yes'
]));

$group = $validation->validate([
    'terms' => 'no',
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // terms必须为yes
}
```

- 验证器`\Snow\Validation\Validation\Validator\InclusionIn`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_DOMAIN | domain | - | 值范围 | 是 |
| OPT_STRICT | strict | false | 是否严格校验 | 否 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\InclusionIn;

$validation = new Validation();
$validation->add('type', new InclusionIn([
    InclusionIn::OPT_DOMAIN => [1, 2, 3]
]));

$group = $validation->validate([
    'type' => 4,
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // type必须在1,2,3范围内
}
```

- 验证器`\Snow\Validation\Validation\Validator\Regex`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_PATTERN | pattern | - | 正则表达式 | 是 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Regex;

$validation = new Validation();
$validation->add('phone', new Regex([
    Regex::OPT_PATTERN => '/^1[3-9]\d{9}$/'
]));

$group = $validation->validate([
    'phone' => '123456789',
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // phone匹配失败
}
```

- 验证器`\Snow\Validation\Validation\Validator\StringLength`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_MIN | min | - | 最小值选项 | min、max、equal最少设置一个 |
| OPT_MESSAGE_MIN | messageMin | - | 字符串长度最小值错误提示信息 | 否 |
| OPT_INCLUDE_MIN | includedMin | true | 字符串长度最小值范围是否包含最小值 | 否 |
| OPT_MAX | max | - | 最大值选项 | 同min选项 |
| OPT_MESSAGE_MAX | messageMax | - | 字符串长度最大值错误提示信息 | 否 |
| OPT_INCLUDE_MAX | includedMax | true | 字符串长度最大值范围是否包含最大值 | 否 |
| OPT_EQUAL | equal | - | 字符串长度相等值 | 同min选项 |
| OPT_MESSAGE_EQUAL | messageEqual | - | 字符串长度相等值错误提示信息 | 否 |

**用法1:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\StringLength;

$validation = new Validation();
$validation->add('name', new StringLength([
    StringLength::OPT_MAX => 10,
]));

$group = $validation->validate([
    'name' => 'adminadminadmin',
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // name长度不能超过10个字符
}
```

**用法2:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\StringLength;

$validation = new Validation();
$validation->add('name', new StringLength([
    StringLength::OPT_MIN => 5,
    StringLength::OPT_MAX => 10,
    StringLength::OPT_MESSAGE_MAX => ':field最大长度为:length',
]));

$group = $validation->validate([
    'name' => 'adminadminadmin',
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // name最大长度为10
}
```

**用法3:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\StringLength;

$validation = new Validation();
$validation->add(['name', 'username'], new StringLength([
    StringLength::OPT_MIN => 5,
    StringLength::OPT_MAX => [
        'name' => 10,
        'username' => 20,
    ],
    StringLength::OPT_MESSAGE_MIN => [
        'name' => '姓名最小长度为:length',
        'username' => '用户名最小长度为:length',
    ],
    StringLength::OPT_MESSAGE_MAX =>':field最大长度为:length',
]));

$group = $validation->validate([
    'name' => 'adminadminadmin',
    'username' => 'adm',
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // name最大长度为10
                        // 用户名最小长度为5
}
```

- 验证器`\Snow\Validation\Validation\Validator\Uniqueness`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_MODEL | model | - | 模型 | 是 |
| OPT_METHOD | method | isUniqueness | 验证方法 | 否 |
| OPT_ATTRIBUTE | attribute | - | 字段映射 | 否 |
| OPT_CONVERT | convert | - | 数据转换处理 | 否 |

**用法1:**
```php
class Model
{
    /**
     * 功能：验证唯一值(默认)
     * 修改日期：2019/8/27
     *
     * @param array $data 数据
     * @param array $filed 字段
     * @return bool
     */
    public function isUniqueness($data, $filed)
    {
        return false;
    }

    /**
     * 功能：验证唯一值（需指定）
     * 修改日期：2019/8/27
     *
     * @param array $data 数据
     * @param array $filed 字段
     * @return bool
     */
    public function isUniqueness2($data, $filed)
    {
        return false;
    }
}



// 使用
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Uniqueness;

$validation = new Validation();
$validation->add('name', new Uniqueness([
    'model' => new Model(),
]));

$group = $validation->validate([
    'name' => 'admin'
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // name已存在
}
```

**用法2:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Uniqueness;

$validation = new Validation();
$validation->add(['name', 'status'], new Uniqueness([
    Uniqueness::OPT_MODEL => new Model(),
    Uniqueness::OPT_METHOD => 'isUniqueness2', // 非必须，当Model中无isUniqueness方法时，需指定方法
    Uniqueness::OPT_ATTRIBUTE => [
        'name' => 'username'
    ],
    Uniqueness::OPT_CONVERT => function ($data) {
        if (isset($data['name'])) {
            $data['name'] = ucfirst($data['name']);
        }
        return $data;
    }
]));

$group = $validation->validate([
    'name' => 'admin',
    'status' => 0,
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // name,status已存在
}
```

- 验证器`\Snow\Validation\Validation\Validator\File`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_ALLOW_TYPES | allowedTypes | - | 允许的文件类型 | minSize、maxSize、equalSize、<br>minResolution、maxResolution、equalResolution、<br>allowedTypes最少设置一个 |
| OPT_MESSAGE_TYPE | messageType | - | 类型错误提示信息 | 否 |
| OPT_MIN_SIZE | minSize | - | 文件大小最小值 | 同allowedTypes选项 |
| OPT_MESSAGE_MIN_SIZE | messageMinSize | - | 文件大小最小值错误提示信息 | 否 |
| OPT_INCLUDE_MIN_SIZE | includedMinSize | true | 文件大小最小值范围是否包含最小值 | 否 |
| OPT_MAX_SIZE | maxSize | - | 文件大小最大值 | 同allowedTypes选项 |
| OPT_MESSAGE_MAX_SIZE | messageMaxSize | - | 文件大小最大值错误提示信息 | 否 |
| OPT_INCLUDE_MAX_SIZE | includedMaxSize | true | 文件大小最大值范围是否包含最大值 | 否 |
| OPT_EQUAL_SIZE | equalSize | - | 文件大小值 | 同allowedTypes选项 |
| OPT_MESSAGE_EQUAL_SIZE | messageEqualSize | - | 文件大小值错误提示信息 | 否 |
| OPT_MIN_RESOLUTION | minResolution | - | 文件分辨率最小值 | 同allowedTypes选项 |
| OPT_MESSAGE_MIN_RESOLUTION | messageMinResolution | - | 文件分辨率最小值错误提示信息 | 否 |
| OPT_INCLUDE_MIN_RESOLUTION | includedMinResolution | true | 文件分辨率最小值范围是否包含最小值 | 否 |
| OPT_MAX_RESOLUTION | maxResolution | - | 文件分辨率最大值 | 同allowedTypes选项 |
| OPT_MESSAGE_MAX_RESOLUTION | messageMaxResolution | - | 文件分辨率最大值错误提示信息 | 否 |
| OPT_INCLUDE_MAX_RESOLUTION | includedMaxResolution | true | 文件分辨率最大值范围是否包含最大值 | 否 |
| OPT_EQUAL_RESOLUTION | equalResolution | - | 文件分辨率值 | 同allowedTypes选项 |
| OPT_MESSAGE_EQUAL_RESOLUTION | messageEqualResolution | - | 文件分辨率值错误提示信息 | 否 |

**用法1:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\File;

$validation = new Validation();
$validation->add('image', new File([
    File::OPT_ALLOW_TYPES => ['image/jpeg'],
]));

$group = $validation->validate($_FILES);

foreach ($group as $msg) {
    echo $msg, '<br>'; // image的文件类型必须为image/jpeg
}
```

**用法2:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\File;

$validation = new Validation();
$validation->add('image', new File([
    File::OPT_MIN_SIZE => '3M',
    File::OPT_INCLUDE_MIN_SIZE => false,
    File::OPT_MESSAGE_MIN_SIZE => '图片最小为:size',
]));

$group = $validation->validate($_FILES);

foreach ($group as $msg) {
    echo $msg, '<br>'; // 图片最小为3M
}
```

**用法3:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\File;

$validation = new Validation();
$validation->add('image', new File([
    File::OPT_MAX_SIZE => '100KB',
]));

$group = $validation->validate($_FILES);

foreach ($group as $msg) {
    echo $msg, '<br>'; // image的文件大小不能大于100KB
}
```

**用法4:**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\File;

$validation = new Validation();
$validation->add('image', new File([
    File::OPT_MIN_RESOLUTION => '953x592',
]));

$group = $validation->validate($_FILES);

foreach ($group as $msg) {
    echo $msg, '<br>'; // image的图像分辨率不能小于953x592
}
```

- 验证器`\Snow\Validation\Validation\Validator\Ip`选项

| 类常量 | 参数 | 默认值 | 注释 | 是否必须 |
| --- | --- | --- | --- | --- |
| OPT_VERSION | version | FILTER_FLAG_IPV4&nbsp;&#124;&nbsp;FILTER_FLAG_IPV6 | ip地址格式 | 否 |
| OPT_ALLOW_PRIVATE | allowPrivate | false | 要求值是 RFC 指定的私域 IP | 否 |
| OPT_ALLOW_RESERVED | allowPrivate | false | 要求值不在保留的 IP 范围内 | 否 |
**使用：**
```php
use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\InclusionIn;

$validation = new Validation();
$validation->add('ip', new Ip());

$group = $validation->validate([
    'ip' => '3..115.201.2'
]);

foreach ($group as $msg) {
    echo $msg, PHP_EOL; // ip必须是有效的IP地址
}
```


