PHP 设计模式
https://www.awaimai.com/patterns

设计模式提取了共同问题，定义了经过测试的解决方案，并描述了可能的结果。
也就是说，设计模式（Patterns）是一些可以在项目中重复使用的解决方案。
但是，设计模式并非像组件那样，能被合并来构建系统的固定的解决方案，它们是解决一般性问题的通用方法。
通俗来说，设计模式就是一些编程的套路，不是具体的攻防动作，是为设计更好系统设计的解决方案

面向对象的原则是“组合优于继承”，因为组合可以以多种方式动态的处理任务。
虽然对象的组合会导致代码的可读性下降，但会让系统更加灵活，复用性更高。
设计模式是很多前辈花费大量精力总结的经验，是经过检验的高效的一系列对象组合方式。

共12种经典的设计模式：

一.基础
UML类图详解
UML类图是一种结构图，用于描述一个系统的静态结构。类图以反映类结构和类之间关系为目的，用以描述软件系统的结构，是一种静态建模方法。类图中的类，与面向对象语言中的类的概念是对应的。

1 类结构
在类的UML图中，使用长方形描述一个类的主要构成，长方形垂直地分为三层，以此放置类的名称、属性和方法。
 ___________________________________
|				User				|
|———————————————————————————————————|
|	+$name:string					|
|   #$age:int                       |
|   -$email                         |
|———————————————————————————————————|
|	+getName():string				|
|   #getAge()                       |
|   -getEmail()                     |
|___________________________________|


其中，
一般类的类名用正常字体粗体表示，如上图；抽象类名用斜体字粗体，如User；接口则需在上方加上<<interface>>。
属性和方法都需要标注可见性符号，+代表public，#代表protected，-代表private。
另外，还可以用冒号:表明属性的类型和方法的返回类型，如+$name:string、+getName():string。当然，类型说明并非必须。

2 类关系
类与类之间的关系主要有六种：继承、实现、组合、聚合、关联和依赖，这六种关系的箭头表示如下，
继承   子类   ——————————|> 父类
实现   实现类 ----------|> 接口
组合   部分类 ——————————<.> 整体类
聚合   部分类 ——————————< > 整体类
关联   引用类 ——————————> 被引用类
依赖   引用类 ----------> 被依赖类

3 六种类关系
六种类关系中，组合、聚合、关联这三种类关系的代码结构一样，都是用属性来保存另一个类的引用，所以要通过内容间的关系来区别。

3.1 继承
继承关系也称泛化关系（Generalization），用于描述父类与子类之间的关系。父类又称作基类，子类又称作派生类。

继承关系中，子类继承父类的所有功能，父类所具有的属性、方法，子类应该都有。子类中除了与父类一致的信息以外，还包括额外的信息。

例如：公交车、出租车和小轿车都是汽车，他们都有名称，并且都能在路上行驶
<?php

class Car
{
    public $name;
    public function run()
    {
        return '在行驶中';
    }
}

class Bus extends Car
{
    public function __construct()
    {
        $this->name = '公交车';
    }
}

class Taxi extends Car
{
    public function __construct()
    {
        $this->name = '出租车';
    }
}

// 客户端代码
$line2 = new Bus;
echo $line2->name . $line2->run();

?>

3.2 实现
实现关系（Implementation），主要用来规定接口和实现类的关系。

接口（包括抽象类）是方法的集合，在实现关系中，类实现了接口，类中的方法实现了接口声明的所有方法。

例如：汽车和轮船都是交通工具，而交通工具只是一个可移动工具的抽象概念，船和车实现了具体移动的功能

<?php

interface Vehicle
{
    public function run();
}

class Car implements Vehicle
{
    public $name = '汽车';
    public function run()
    {
        return $this->name . '在路上行驶';
    }
}

class Ship implements Vehicle
{
    public $name = '轮船';
    public function run()
    {
        return $this->name . '在海上航行';
    }
}

// 客户端代码
$car = new Car;
echo $car->run();

?>

3.3 组合关系
组合关系（Composition）：整体与部分的关系，但是整体与部分不可以分开。

组合关系表示类之间整体与部分的关系，整体和部分有一致的生存期。一旦整体对象不存在，部分对象也将不存在，是同生共死的关系。

例如：人由头部和身体组成，两者不可分割，共同存在。

<?php

class Head
{
    public $name = '头部';
}

class Body
{
    public $name = '身体';
}

class Human
{
    public $head;
    public $body;

    public function setHead(Head $head)
    {
        $this->head = $head;
    }

    public function setBody(Body $body)
    {
        $this->body = $body;
    }

    public function display()
    {
        return sprintf('人由%s和%s组成', $this->head->name, $this->body->name);
    }
}

// 客户端代码
$man = new Human();
$man->setHead(new Head());
$man->setBody(new Body());
echo $man->display();

?>

3.4 聚合关系
聚合关系（Aggregation）：整体和部分的关系，整体与部分可以分开。

聚合关系也表示类之间整体与部分的关系，成员对象是整体对象的一部分，但是成员对象可以脱离整体对象独立存在。

例如：公交车司机和工衣、工帽是整体与部分的关系，但是可以分开，工衣、工帽可以穿在别的司机身上，公交司机也可以穿别的工衣、工帽。
<?php

class Clothes
{
    public $name = '工衣';
}

class Hat
{
    public $name = '工帽';
}

class Driver
{
    public $clothes;
    public $hat;

    public function wearClothes(Clothes $clothes)
    {
        $this->clothes = $clothes;
    }

    public function wearHat(Hat $hat)
    {
        $this->hat = $hat;
    }

    public function show()
    {
        return sprintf('公交车司机穿着%s和%s', $this->clothes->name, $this->hat->name);
    }
}

// 客户端代码
$driver = new Driver();
$driver->wearClothes(new Clothes());
$driver->wearHat(new Hat());
echo $driver->show();

?>


3.5 关联关系
关联关系（Association）：表示一个类的属性保存了对另一个类的一个实例（或多个实例）的引用。

关联关系是类与类之间最常用的一种关系，表示一类对象与另一类对象之间有联系。组合、聚合也属于关联关系，只是关联关系的类间关系比其他两种要弱。

关联关系有四种：双向关联、单向关联、自关联、多重数关联。

例如：汽车和司机，一辆汽车对应特定的司机，一个司机也可以开多辆车。
<?php

class Driver
{
    public $cars = array();
    public function addCar(Car $car)
    {
        $this->cars[] = $car;
    }
}

class Car
{
    public $drivers = array();
    public function addDriver(Driver $driver)
    {
        $this->drivers[] = $driver;
    }
}

// 客户端代码
$jack = new Driver();
$line1 = new Car();
$jack->addCar($line1);
$line1->addDriver($jack);
print_r($jack);

?>

在多重性关系中，可以直接在关联直线上增加一个数字，表示与之对应的另一个类的对象的个数。

1..1：仅一个
0..*：零个或多个
1..*：一个或多个
0..1：没有或只有一个
m..n：最少m、最多n个 (m<=n)

3.6 依赖关系
依赖关系（Dependence）：假设A类的变化引起了B类的变化，则说名B类依赖于A类。

大多数情况下，依赖关系体现在某个类的方法使用另一个类的对象作为参数。

依赖关系是一种“使用”关系，特定事物的改变有可能会影响到使用该事物的其他事物，在需要表示一个事物使用另一个事物时使用依赖关系。

例如：汽车依赖汽油，如果没有汽油，汽车将无法行驶
<?php
class Oil
{
    public $type = '汽油';
    public function add()
    {
        return $this->type;
    }
}

class Car
{
    public function beforeRun(Oil $oil)
    {
        return '添加' . $oil->add();
    }
}

// 客户端代码
$car = new Car;
echo $car->beforeRun(new Oil());

?>

4 总结
这六种类关系中，组合、聚合和关联的代码结构一样，可以从关系的强弱来理解，各类关系从强到弱依次是：继承→实现→组合→聚合→关联→依赖。如下是完整的一张UML关系图。
UML类图是面向对象设计的辅助工具，但并非是必须工具，如果暂时不理解本文的内容，可以继续看设计模式部分，并不会影响。

说明：本文所有UML类图均使用免费的UMLet工具，http://www.umlet.com/
















