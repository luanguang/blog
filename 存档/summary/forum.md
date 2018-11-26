forum项目是根据laracasts的testing里的学习教程一步步搭建而成。
## 加深理解
laravel可以不用observer，直接在模型里定义一个boot方法就能使用自带的十种监听事件的方法，个人感觉简单的处理可以直接放在模型当中，而处理比较多的时候，建议还是合并到一个observer里，在serviceprovider里记得注册。
工厂模式生成数据，在Modelfactory中，define生成多种不同数据，使用`php artisan tinker`创建定义的数据。
## 普通理解
测试用例，laravel自带登录的方法`actingAs`，不同`guarded`可以传入不同的参数进行模拟登录操作。不过没有尝试过用jwt方式。测试时，因为phpunit版本问题出过许多bug，例如`assertDatabaseMission`这方法不存在之类的无法解决。
laravel定义了许多断言方法供你测试返回的数据是否正确。
## 一知半解
Gate的使用，定义某个固定的用户为管理员权限。
每次执行控制器，都会先访问模型当中有没有跟引入trait一样名字的boot方法。
在serviceprovider当中用`composer`方法共享数据，可以传入到缓存当中，只需查询一次，不比多次查询，优化查询效率。
定义`policy`，控制部分方法的权限，比如说update只能是管理员或者这个文章或者回复的发起人。
laravel自带的提醒notify监控。
发送邮件所需配置，以及驱动设置。

## 缺点
知识点过多，导致分散。以及重构将一眼能看完的代码分成了多个地方，虽然感觉代码更少，更整洁。但是过几天重新打开这个项目再回味，发现忘记的也差不多了，还要来回查找这个方法哪里来的。新人有些难受。


### 多态关联
一个用户可以创建文章，也可以发表评论。这些都记录在活动表当中。所以活动表当中有三个字段。
```
user_id
subject_id
subject_type

class Activity extends Model
{
    public function subject()
    {
        return $this->morphTo();
    }
}

在thread和reply模型当中都定义
    protected function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
```

> twice
