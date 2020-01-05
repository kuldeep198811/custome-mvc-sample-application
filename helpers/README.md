=======================================Data_helper=====================
Data_helper class contains following methods:-

1. __dump($_dataArray=array());
=>  function to generate data in array format	

2. __get_random_string($_length = 10);
=> function to generate random string

3. __split_uri($_uriArray);
=>  function to split uri array by delimeter '/'

4. __mssql_escape($_str);
=> for escaping single quotes from string

5. __paginate($_item_per_page, $_current_page, $_total_records, $_total_pages, $_page_url);
=> To create pagination html  

6. __convertCurrency($_number);
=> Convert Price to Crores or Lakhs or Thousands

7. __toObject($_array); //$array is a multidimensional array
=> Converts an array to an object.

8. __dumpStr($_var);
=> Converts a string or an object to an array.

9. __first($_array);
=> Returns the first element of an array.

10. __last($_array);
=> Returns the first element of an array.

11. __array_get($_key, $_array);
=>  Gets a value in an array by dot notation for the keys.

12. __array_set($_key, $_value, &$_array);
=> Sets a value in an array using the dot notation.

13. __contains($_needle, $_haystack);
=> Tests if a string contains a given element. Ignore case sensitivity.

14. __afterLast($search, $string)

============================BreadCrumb_helper.php==========================
=> For generating breadcrumbs

There are 2 methods:

* __add()
* __render()
add() will add each breadcrumbs to the stack render() will generate the full breadcrumb.

Manual Loading: You can alternatively load the library in the controller itself before calling its methods.

require 'BreadCrumb_helper.php';
Then, from your controller, add the following lines

$this->BreadCrumb_helper->__add('Home', base_url());
$this->BreadCrumb_helper->__add('Cities', base_url('cities/listing'));
which will add each lines submitted in an array. Finally, to get the breadcrumbs, use the render() method.

$this->BreadCrumb_helper->__render();
Assign it to a variable and pass it to your views for output.

Breadcrumbs helps us trace our way back, starting from the current page and ideally up to the homepage. It is commonsense that the last link is the current page, and it should not be clickable. This will be handled automatically, where the last link will be non-clickable.