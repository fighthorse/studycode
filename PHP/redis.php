<?php
/**
 * https://github.com/phpredis/phpredis
 * 
 */


/*1.Connection*/
   $redis = new Redis();
   $redis->connect('127.0.0.1',6379,1);//短链接，本地host，端口为6379，超过1秒放弃链接
   $redis->open('127.0.0.1',6379,1);//短链接(同上)
   $redis->pconnect('127.0.0.1',6379,1);//长链接，本地host，端口为6379，超过1秒放弃链接
   $redis->popen('127.0.0.1',6379,1);//长链接(同上)
   $redis->auth('password');//登录验证密码，返回【true | false】
   $redis->select(0);//选择redis库,0~15 共16个库
   $redis->close();//释放资源
   $redis->ping(); //检查是否还再链接,[+pong]
   $redis->ttl('key');//查看失效时间[-1 | timestamps]
   $redis->persist('key');//移除失效时间[ 1 | 0]
   $redis->sort('key',[$array]);//返回或保存给定列表、集合、有序集合key中经过排序的元素，$array为参数limit等！【配合$array很强大】 [array|false]


/*2.共性的运算归类*/
   $redis->expire('key',10);//设置失效时间[true | false]
   $redis->move('key',15);//把当前库中的key移动到15库中[0|1]

//string
   $redis->strlen('key');//获取当前key的长度
   $redis->append('key','string');//把string追加到key现有的value中[追加后的个数]
   $redis->incr('key');//自增1，如不存在key,赋值为1(只对整数有效,存储以10进制64位，redis中为str)[new_num | false]
   $redis->incrby('key',$num);//自增$num,不存在为赋值,值需为整数[new_num | false]
   $redis->decr('key');//自减1，[new_num | false]
   $redis->decrby('key',$num);//自减$num，[ new_num | false]
   $redis->setex('key',10,'value');//key=value，有效期为10秒[true]
//list
   $redis->llen('key');//返回列表key的长度,不存在key返回0， [ len | 0]
//set
   $redis->scard('key');//返回集合key的基数(集合中元素的数量)。[num | 0]
   $redis->sMove('key1', 'key2', 'member');//移动，将member元素从key1集合移动到key2集合。[1 | 0]
//Zset
   $redis->zcard('key');//返回集合key的基数(集合中元素的数量)。[num | 0]
   $redis->zcount('key',0,-1);//返回有序集key中，score值在min和max之间(默认包括score值等于min或max)的成员。[num | 0]
//hash
   $redis->hexists('key','field');//查看hash中是否存在field,[1 | 0]
   $redis->hincrby('key','field',$int_num);//为哈希表key中的域field的值加上量(+|-)num,[new_num | false]
   $redis->hlen('key');//返回哈希表key中域的数量。[ num | 0]



/*3.Server*/
   $redis->dbSize();//返回当前库中的key的个数
   $redis->flushAll();//清空整个redis[总true]
   $redis->flushDB();//清空当前redis库[总true]
   $redis->save();//同步??把数据存储到磁盘-dump.rdb[true]
   $redis->bgsave();//异步？？把数据存储到磁盘-dump.rdb[true]
   $redis->info();//查询当前redis的状态 [verson:2.4.5....]
   $redis->lastSave();//上次存储时间key的时间[timestamp]

   $redis->watch('key','keyn');//监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断 [true]
   $redis->unwatch('key','keyn');//取消监视一个(或多个) key [true]
   $redis->multi(Redis::MULTI);//开启事务，事务块内的多条命令会按照先后顺序被放进一个队列当中，最后由 EXEC 命令在一个原子时间内执行。
   $redis->multi(Redis::PIPELINE);//开启管道，事务块内的多条命令会按照先后顺序被放进一个队列当中，最后由 EXEC 命令在一个原子时间内执行。
   $redis->exec();//执行所有事务块内的命令，；【事务块内所有命令的返回值，按命令执行的先后顺序排列，当操作被打断时，返回空值 false】



/*4.String，键值对，创建更新同操作*/
   $redis->setOption(Redis::OPT_PREFIX,'hf_');//设置表前缀为hf_
   $redis->set('key',1);//设置key=aa value=1 [true]
   $redis->mset($arr);//设置一个或多个键值[true]
   $redis->setnx('key','value');//key=value,key存在返回false[|true]
   $redis->get('key');//获取key [value]
   $redis->mget($arr);//(string|arr),返回所查询键的值
   $redis->del($key_arr);//(string|arr)删除key，支持数组批量删除【返回删除个数】
   $redis->delete($key_str,$key2,$key3);//删除keys,[del_num]
   $redis->getset('old_key','new_value');//先获得key的值，然后重新赋值,[old_value | false]



/*5.List栈的结构,注意表头表尾,创建更新分开操作*/
   $redis->lpush('key','value');//增，只能将一个值value插入到列表key的表头，不存在就创建 [列表的长度 |false]
   $redis->rpush('key','value');//增，只能将一个值value插入到列表key的表尾 [列表的长度 |false]
   $redis->lInsert('key', Redis::AFTER, 'value', 'new_value');//增，将值value插入到列表key当中，位于值value之前或之后。[new_len | false]
   $redis->lpushx('key','value');//增，只能将一个值value插入到列表key的表头，不存在不创建 [列表的长度 |false]
   $redis->rpushx('key','value');//增，只能将一个值value插入到列表key的表尾，不存在不创建 [列表的长度 |false]
   $redis->lpop('key');//删，移除并返回列表key的头元素,[被删元素 | false]
   $redis->rpop('key');//删，移除并返回列表key的尾元素,[被删元素 | false]
   $redis->lrem('key','value',0);//删，根据参数count的值，移除列表中与参数value相等的元素count=(0|-n表头向尾|+n表尾向头移除n个value)  [被移除的数量 | 0]
   $redis->ltrim('key',start,end);//删，列表修剪，保留(start,end)之间的值 [true|false]
   $redis->lset('key',index,'new_v');//改，从表头数，将列表key下标为第index的元素的值为new_v, [true | false]
   $redis->lindex('key',index);//查，返回列表key中，下标为index的元素[value|false]
   $redis->lrange('key',0,-1);//查，(start,stop|0,-1)返回列表key中指定区间内的元素，区间以偏移量start和stop指定。[array|false]

/*6.Set，没有重复的member，创建更新同操作*/
   $redis->sadd('key','value1','value2','valuen');//增，改，将一个或多个member元素加入到集合key当中，已经存在于集合的member元素将被忽略。[insert_num]
   $redis->srem('key','value1','value2','valuen');//删，移除集合key中的一个或多个member元素，不存在的member元素会被忽略 [del_num | false]
   $redis->smembers('key');//查，返回集合key中的所有成员 [array | '']
   $redis->sismember('key','member');//判断member元素是否是集合key的成员 [1 | 0]
   $redis->spop('key');//删，移除并返回集合中的一个随机元素 [member | false]
   $redis->srandmember('key');//查，返回集合中的一个随机元素 [member | false]
   $redis->sinter('key1','key2','keyn');//查，返回所有给定集合的交集 [array | false]
   $redis->sunion('key1','key2','keyn');//查，返回所有给定集合的并集 [array | false]
   $redis->sdiff('key1','key2','keyn');//查，返回所有给定集合的差集 [array | false]


/*7.Zset，没有重复的member，有排序顺序,创建更新同操作*/
   $redis->zAdd('key',$score1,$member1,$scoreN,$memberN);//增，改，将一个或多个member元素及其score值加入到有序集key当中。[num | 0]
   $redis->zrem('key','member1','membern');//删，移除有序集key中的一个或多个成员，不存在的成员将被忽略。[del_num | 0]
   $redis->zscore('key','member');//查,通过值反拿权 [num | null]
   $redis->zrange('key',$start,$stop);//查，通过(score从小到大)【排序名次范围】拿member值，返回有序集key中，【指定区间内】的成员 [array | null]
   $redis->zrevrange('key',$start,$stop);//查，通过(score从大到小)【排序名次范围】拿member值，返回有序集key中，【指定区间内】的成员 [array | null]
   $redis->zrangebyscore('key',$min,$max[,$config]);//查，通过scroe权范围拿member值，返回有序集key中，指定区间内的(从小到大排)成员[array | null]
   $redis->zrevrangebyscore('key',$max,$min[,$config]);//查，通过scroe权范围拿member值，返回有序集key中，指定区间内的(从大到小排)成员[array | null]
   $redis->zrank('key','member');//查，通过member值查(score从小到大)排名结果中的【member排序名次】[order | null]
   $redis->zrevrank('key','member');//查，通过member值查(score从大到小)排名结果中的【member排序名次】[order | null]
   $redis->ZINTERSTORE();//交集
   $redis->ZUNIONSTORE();//差集

/*8.Hash，表结构，创建更新同操作*/
   $redis->hset('key','field','value');//增，改，将哈希表key中的域field的值设为value,不存在创建,存在就覆盖【1 | 0】
   $redis->hget('key','field');//查，取值【value|false】
   $arr = array('one'=>1,2,3);$arr2 = array('one',0,1);
   $redis->hmset('key',$arr);//增，改，设置多值$arr为(索引|关联)数组,$arr[key]=field, [ true ]
   $redis->hmget('key',$arr2);//查，获取指定下标的field，[$arr | false]
   $redis->hgetall('key');//查，返回哈希表key中的所有域和值。[当key不存在时，返回一个空表]
   $redis->hkeys('key');//查，返回哈希表key中的所有域。[当key不存在时，返回一个空表]
   $redis->hvals('key');//查，返回哈希表key中的所有值。[当key不存在时，返回一个空表]
   $redis->hdel('key',$arr2);//删，删除指定下标的field,不存在的域将被忽略,[num | false]
/**
 * Pub/sub 发布订阅模块
 */
$redis->publish('chan-1', 'hello, world!'); // send message.

function f($redis, $chan, $msg) {
   switch($chan) {
      case 'chan-1':
         ...
         break;

      case 'chan-2':
         ...
         break;

      case 'chan-2':
         ...
         break;
   }
}
$redis->subscribe(array('chan-1', 'chan-2', 'chan-3'), 'f'); // subscribe to 3 chans

//pubSub A command allowing you to get information on the Redis pub/sub system.
$redis->pubSub("channels"); /*All channels */
$redis->pubSub("channels", "*pattern*"); /* Just channels matching your pattern */
$redis->pubSub("numsub", Array("chan1", "chan2")); /*Get subscriber counts for 'chan1' and 'chan2'*/
$redis->pubSub("numpat"); /* Get the number of pattern subscribers */


//rawCommand A method to execute any arbitrary command against the a Redis server
/* Returns: true */
$redis->rawCommand("set", "foo", "bar");
/* Returns: "bar" */
$redis->rawCommand("get", "foo");
/* Returns: 3 */
$redis->rawCommand("rpush", "mylist", "one", 2, 3.5));
/* Returns: ["one", "2", "3.5000000000000000"] */
$redis->rawCommand("lrange", "mylist", 0, -1);

//Transactions 事务 Enter and exit transactional mode.
 $ret = $redis->multi()
    ->set('key1', 'val1')
    ->get('key1')
    ->set('key2', 'val2')
    ->get('key2')
    ->exec();
/*
$ret == array(
    0 => TRUE,
    1 => 'val1',
    2 => TRUE,
    3 => 'val2');
*/

// The last error message (if any)
$err = $redis->getLastError();
$redis->clearLastError();

//设置前缀
$redis->setOption(Redis::OPT_PREFIX, 'my-prefix:');
$redis->_prefix('my-value'); // Will return 'my-prefix:my-value'

//_serialize  _unserialize
//This method allows you to serialize a value with whatever serializer is configured, manually. 
//This can be useful for serialization/unserialization of data going in and out of EVAL commands as phpredis can't automatically do this itself.
// Note that if no serializer is set, phpredis will change Array values to 'Array', and Objects to 'Object'.
$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
$redis->_serialize("foo"); // returns "foo"
$redis->_serialize(Array()); // Returns "Array"
$redis->_serialize(new stdClass()); // Returns "Object"

$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
$redis->_serialize("foo"); // Returns 's:3:"foo";'

$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
$redis->_unserialize('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}'); // Will return Array(1,2,3)

//isConnected
//A method to determine if a phpredis object thinks it's connected to a server
//getTimeout
// Get the (write) timeout in use for phpredis
//getHost
//Retrieve our host or unix socket that we're connected to
//getAuth
//Get the password used to authenticate the phpredis connection
/*
PHP Session handler
phpredis can be used to store PHP sessions. To do this, configure session.save_handler and session.save_path in your php.ini to tell phpredis where to store the sessions:

session.save_handler = redis
session.save_path = "tcp://host1:6379?weight=1, tcp://host2:6379?weight=2&timeout=2.5, tcp://host3:6379?weight=2&read_timeout=2.5"
session.save_path can have a simple host:port format too, but you need to provide the tcp:// scheme if you want to use the parameters. The following parameters are available:

weight (integer): the weight of a host is used in comparison with the others in order to customize the session distribution on several hosts. If host A has twice the weight of host B, it will get twice the amount of sessions. In the example, host1 stores 20% of all the sessions (1/(1+2+2)) while host2 and host3 each store 40% (2/1+2+2). The target host is determined once and for all at the start of the session, and doesn't change. The default weight is 1.
timeout (float): the connection timeout to a redis host, expressed in seconds. If the host is unreachable in that amount of time, the session storage will be unavailable for the client. The default timeout is very high (86400 seconds).
persistent (integer, should be 1 or 0): defines if a persistent connection should be used. (experimental setting)
prefix (string, defaults to "PHPREDIS_SESSION:"): used as a prefix to the Redis key in which the session is stored. The key is composed of the prefix followed by the session ID.
auth (string, empty by default): used to authenticate with the server prior to sending commands.
database (integer): selects a different database.
Sessions have a lifetime expressed in seconds and stored in the INI variable "session.gc_maxlifetime". You can change it with ini_set(). The session handler requires a version of Redis with the SETEX command (at least 2.0). phpredis can also connect to a unix domain socket: session.save_path = "unix:///var/run/redis/redis.sock?persistent=1&weight=1&database=0.
*/



?>