设计模式

创建型模式、结构性模式、行为型模式

创建型模式
对类的实例化过程进行了抽象，将软件模块中对象的创建和对象的使用分离。
为了使软件的结构更加清晰，外界对于这些对象只需要知道它们共同的接口，而不需要清楚其具体的实现细节。
这样就使得整个系统的设计更加符合单一职责原则。
创建型模式在创建什么(What)，由谁创建(Who)，何时创建(When)等方面都为软件设计者提供了尽可能大的灵活性。
创建型模式隐藏了类的实例的创建细节，通过隐藏对象如何被创建，和组合在一起达到使整个系统独立的目的。

简单工厂，工厂方法，抽象工厂都属于设计模式中的创建型模式。

简单工厂模式

简单工厂模式的工厂类一般是使用静态方法，通过接收的参数的不同来返回不同的对象实例。
不修改代码的话，是无法扩展的。

1 类实现
比如，我们有一些类，它们都继承自交通工具类：
<?php
interface Vehicle
{
    public function drive();
}

class Car implements Vehicle
{
    public function drive()
    {
        echo '汽车靠四个轮子滚动行走。';
    }
}

class Ship implements Vehicle
{
    public function drive()
    {
        echo '轮船靠螺旋桨划水前进。';
    }
}

class Aircraft implements Vehicle
{
    public function drive()
    {
        echo '飞机靠螺旋桨和机翼的升力飞行。';
    }
}
//再创建一个工厂类，专门用作类的创建，：

class VehicleFactory
{
    public static function build($className = null)
    {
        $className = ucfirst($className);
        if ($className && class_exists($className)) {
            return new $className();
        }
        return null;
    }
}
//工厂类用了一个静态方法来创建其他类，在客户端中就可以这样使用：

VehicleFactory::build('Car')->drive();
VehicleFactory::build('Ship')->drive();
VehicleFactory::build('Aircraft')->drive();

//省去了每次都要new类的工作。
?>


工厂方法

工厂方法是针对每一种产品提供一个工厂类。通过不同的工厂实例来创建不同的产品实例。
在同一等级结构中，支持增加任意产品。






抽象工厂

抽象工厂是应对产品族概念的。比如说，每个汽车公司可能要同时生产轿车，货车，客车，那么每一个工厂都要有创建轿车，货车和客车的方法。
应对产品族概念而生，增加新的产品线很容易，但是无法增加新的产品。



单例模式
正如其名，允许我们创建一个而且只能创建一个对象的类。
这在整个系统的协同工作中非常有用，特别明确了只需一个类对象的时候。
那么，为什么要实现这么奇怪的类，只实例化一次？

在很多场景下会用到，如：配置类、Session类、Database类、Cache类、File类等等。
这些只需要实例化一次，就可以在应用全局中使用。
本文我们以数据库类为例。

1 问题
如果没有使用单例模式，会有什么样的问题？

如下是一个简单的数据库连接类，它没有使用单例模式。

class Database
{
    public $db = null;
    public function __construct($config = array())
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', $config['db_host'], $config['db_name']);
        $this->db = new PDO($dsn, $config['db_user'], $config['db_pass']);
    }
}
然后创建3个对象：

$config = array(
    'db_name' => 'test',
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => 'root'
);

$db1 = new Database($config);
var_dump($db1);
$db2 = new Database($config);
var_dump($db2);
$db3 = new Database($config);
var_dump($db3);
这种情况下，每当我们创建一个这个类的实例，就会新增一个到数据库的连接。

开发者每在一个地方实例化一次这个类，就会在那里多一个数据库连接。

不知不觉中，开发者就犯了个错误，给数据库和服务器性能带来巨大的影响。

上面的代码输入如下：

object(Database)[1]
  public 'db' => object(PDO)[2]
object(Database)[3]
  public 'db' => object(PDO)[4]
object(Database)[5]
  public 'db' => object(PDO)[6]
每个对象都分配一个新的资源ID，都是新的引用，它们占用3个的内存空间。

如果有100个对象创建，就会占用内存中100块不同的空间，而其余99块并非是必须的。

2 解决
开发者怎样使用基础框架，如何数据库连接，这很难控制。

如果在代码评审阶段再找出问题，又会浪费大量的人力物力。

要解决这样的问题，我们可以控制住基类，在源头上限制这个类，使其无法生成多个对象，如果已经生成过，直接返回。

于是，我们的目标就是，控制数据库类，使其生成一次而且只能生成一次对象。

如下就是单例模式连接数据库代码：

class Database
{
    // 声明$instance为私有静态类型，用于保存当前类实例化后的对象
    private static $instance = null;
    // 数据库连接句柄
    private $db = null;

    // 构造方法声明为私有方法，禁止外部程序使用new实例化，只能在内部new
    private function __construct($config = array())
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', $config['db_host'], $config['db_name']);
        $this->db = new PDO($dsn, $config['db_user'], $config['db_pass']);
    }

    // 这是获取当前类对象的唯一方式
    public static function getInstance($config = array())
    {
        // 检查对象是否已经存在，不存在则实例化后保存到$instance属性
        if(self::$instance == null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    // 获取数据库句柄方法
    public function db()
    {
        return $this->db;
    }

    // 声明成私有方法，禁止克隆对象
    private function __clone(){}
    // 声明成私有方法，禁止重建对象
    private function __wakeup(){}
}
再通过getInstance()方法使用类对象，

$config = array(
    'db_name' => 'test',
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => 'root'
);

$db1 = Database::getInstance($config);
var_dump($db1);
$db2 = Database::getInstance($config);
var_dump($db2);
$db3 = Database::getInstance($config);
var_dump($db3);
输出信息如下：

object(Database)[1]
  private 'db' => object(PDO)[2]
object(Database)[1]
  private 'db' => object(PDO)[2]
object(Database)[1]
  private 'db' => object(PDO)[2]
对比两个输出可以看出，单例模式中，不同对象获得的资源ID是一样的。

也就是说，虽然我们用getInstance()获取Database类对象3次，其实引用的是一个内存空间，PDO也只连接了数据库一次。

以上的例子是数据库连接类，要使用数据库，在应用这样获得连接句柄：

$db = database::getInstance($config)->db();
如果是其他类，则按需要修改数据库相关的代码，单例实现部分保留。

3 特点
单例模式的特点是4私1公：一个私有静态属性，构造方法私有，克隆方法私有，重建方法私有，一个公共静态方法。

其他方法根据需要增加。

最基础的单例模式代码如下：

class Singleton
{
    private static $instance = null;

    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}
}
$instance用以保存类的实例化，getInstance()方法提供给外部本类的实例化对象：

对应的UML图如下，

PHP单例模式

单例模式在应用请求的整个生命周期中都有效，这点类似全局变量，会降低程序的可测试性。

大部分情况下，也可以用依赖注入来代替单例模式，避免在应用中引入不必要的耦合。

所以，对于仅需生成一个对象的类，首先考虑用依赖注入方式，其次考虑用单例模式来实现。
