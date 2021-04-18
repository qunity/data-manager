Qunity DataManager
==================

Data Manager makes it easy to work with data for objects.

---

You can recursively work with data from objects using a delimited `/` key

```
$this->set('key/key/key', 'value');
$this->get('key/key') == ['key' => 'value']; // is true
```

You can use magic methods to recursively work with data in to objects

```
$this->setKey_key_key('value');
$this->getKey_key() == ['key' => 'value']; // is true
```

You can work with data in masses and iterate over object as array

```
$this->set(['key/0' => 'value', 'key/1' => 'value']);
$this->get() == ['key' => ['value', 'value']]; // is true
```
