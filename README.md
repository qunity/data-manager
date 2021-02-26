Qunity DataManager
==================

Data Manager makes it easy to work with data for objects.

---

#### You can recursively take or set data from objects using a delimited `/` key

Example:

```
$this->set('key1/key2/key3', 'value');
$this->get('key1/key2/key3') == 'value'; // is true
```

#### You can use magic methods to recursively get or set data in to objects

Example:

```
$this->setKey1_key2_key3('value');
$this->getKey1_key2_key3() == 'value'; // is true
```
