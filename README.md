# susautw-type
Check Types
## 0.1.0
`* Optional`


### TypeArray
Description:
Check elements type in array

| Method | Parameter | Return | Throws | Description |
|---|---|---|---|---|
|**`constructor`**|`type:string` `array*:mixed[]`|void|`ClassNotFoundException` `IncompatibleTypeException`|Determine type of array and initial it.|
|**`checkType`**|`expected:string`|void|`ClassNotFoundException` `IncompatibleTypeException`|Check types in array is or not expected type for use.|
```php
<?php
interface C{}
class B{}
class A extends B implements C{}
$arrC = new TypeArray(C::class);
$arrB = new TypeArray(B::class);
$arrA = new TypeArray(A::class);
$arrN = new TypeArray("string"); //string|integer|double|boolean here
//$arrE = new TypeArray("SomeThingNotExists"); //error!!! class not found.

//init array like this
$arrIA = new TypeArray(A::class,[new A(),new A()]; //ok
//$arrIAE = mew TypeArray(A::class,[new B(),123,"abc"]) //error!!! incompatible type.

$arrC[] = new A; //can assign like this case.

$arrB[] = new A; //ok
$arrA[] = new A; //ok

//$arrA[] = 123; //error!!! incompatible type.

//$arrA[] = new B; //error!!! incompatible type.
$arrB[] = new A; //ok

//$arrN[] = .12; //error!!! incompatible type.

$arrA->checkType(B::class); //ok
//$arrB->checkType(A::class); //error!!! incompatible type.

print 'ok';

```

##### Suggestion in IDE (here is phpstorm)
`php
<?php
class A
{
    public function get():integer
    {
        return 1;
    }
}

//if you write comment like this

/** @var A[]|TypeArray $arr */
$arrA = new TypeArray(A::class);
$arrA[] = new A();

//method get will hint in IDE. (A)
$arrA[0]->get();

//method checkType too. (TypeArray)
$arr->checkType(A::class);
`