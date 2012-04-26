ActiveRecord For Drupal
----------------------------
This module provides an object-oriented relational mapper based on the ActiveRecord pattern.
It is heavily bound to drupal schema, so you'll need to define your tables properly in order to
successfully handle ActiveRecord instances.

Defining models and databases
---------------------------------------------------
When you define a class with, its name MUST be respected in the table hosting its instances. For example,
if you define a class 'Car', then the table in the schema MUST be named "cars". If you define a multi-word class like
"CarDriver", the table in the database must be named "car_drivers", and so on (replacing CamelCase with underscores
and adding an 's' at the end).
You are obligued to include a serial primary key, which must be named "id". If you need to add other unique identifiers
to your models, add them via unique keys.

Loading models
---------------------------------------------------
To load an instance, you can use the static method load().
```php
$car = Car::load($some_id)
```

If you want to load an instance by a non-primary key field, you should use loadBy() which accepts an array with the
selected chriteria.
$car = Car::loadBy(array('model' => 'Celica'));

To load many instances, you can use all(), which results in an array of instances.
$cars = Car::all()
You can filter by some fields
$cars = Car::all(array('max_passengers' => 4, 'diesel' => 0, 'constructor' => 'Ford'));
And you can sort them
$cars = Car::all(array('constructor' => 'Ford'), array('released' => 'asc', 'max_speed' => 'desc'));

Instantiating models
---------------------------------------------------
To instantiate a class, just pass an array with the instance properties to the constructor.
$car_data = array('model' => 'C3', 'constructor' => 'Citröen', 'max_speed' => 170);
$car = new Car($car_data);

Saving/updating models
---------------------------------------------------
Once you've an instance, storing it is straight-forward.
$car->save();
You don't have to worry about saving a new item or an existing one. ActiveRecord will do this for you, and will
populate the instance's id in new instances being saved for first time.

Deleting models
---------------------------------------------------
To delete an object, just make use of the method delete(). It will be deleted from the database, but you will obtain
an annonymous instance, so you could do something like:
$instance->delete()->save(); // Uh, ok, may be doing so is not very useful.
You can call the method delete() on a non-saved instance, but it will do nothing.

Defining relationships
---------------------------------------------------
ONE TO MANY: This is the only relationship implemented at the time. To define relationships between models you will
need to use annotations. For example, to define a relationship between a Mother and her Son, you should code something
like this:

/**
 * @HasMany('Son')
 */
class Mother extends ActiveRecord {}

/**
 * @BelongsTo('Mother')
 */
class Son extends ActiveRecord {}

Automagically, given an instance of Mother, you could do the next without implementing additional logic:
$sons = $mother->sons(); // You'll obtain an array of this mother sons.
$mother = $son->mother(); // You'll obtain a son's mother.
$mother->addSon($son); // Bounds a son to the mother instance
$mother->save(); // Will save/update mother and her respective sons.
$mother->delete(); // Depending on how is your schema definition, it will delete children, if any (cascade) or make
                   // them orphan.
$mother->deleteSons(); // Will delete all its sons.


Using forms
---------------------------------------------------
The activerecord module also provides a form builder. You can keep using the traditional form API way
if you need fine-grained forms with some additional logic, but for many purposes you may prefer to render
an auto-generated form instead of unnecessarily spend coding time. Also, autogenerated forms are much
easier to mantain because they keep themselves unaffected by database changes.

If you want to render an empty form to create an ActiveRecord instace, you just have to write code like this
and it will populate all the necessary fields.
$myobject = new MyClass;
$form = drupal_get_form('activerecord', $myobject);

And if you want to render a form to UPDATE an existing instance, just pass it through drupal_get_form():
$myobject = MyClass::load($id);
$form = drupal_get_form('activerecord', $myobject);

¡As easy as it sounds!


@todo Keep working on model relationships (one-to-one, many-to-many)
@todo eager loading/lazy loading control
@see http://books.google.es/books?id=FyWZt5DdvFkC&lpg=PA1&dq=Patterns+of+Enterprise+Application+Architecture+by+Martin+Fowler&pg=PT187&redir_esc=y#v=onepage&q=active%20record&f=false
