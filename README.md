Qunity DataManager
==================

Data Manager makes it easy to work with data for objects.

---

##### You can recursively work (`set`, `add`, `get`, `has`, `del`) with data from objects using a delimited `/` key

```
$this->set('key1/key2/key3', 'value');
$this->get('key1/key2') == ['key3' => 'value']; // is true
```

##### You can use magic methods to recursively work (`set`, `add`, `get`, `has`, `del`) with data in to objects

```
$this->setKey1_key2_key3('value');
$this->getKey1_key2() == ['key3' => 'value']; // is true
```

##### You can work with data in masses and iterate over object as array

```
$this->set(['key/0' => 'value', 'key/1' => 'value']);
$this->get() == ['key' => ['value', 'value']]; // is true
```
