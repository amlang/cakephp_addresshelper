#Address- Helper v1.0.0 for CakePHP 2.3+

Microdata AddressHelper class for easy working with addresses.

## Table of Contents  
[Requirements](#requirements)  
[Installation](#install)  
[Enable Helper](#enable)  
[Usage](#usage)  
[AddressHelper API](#api)  
[License](#licence)  

<a name="requirements"/>  
##Requirements
* CakePHP 2.x
* PHP 5.3

<a name="install"/>
## Installation
_[Manual]_
* Download this: [http://github.com/amlang/cakephp_addresshelper/zipball/master](http://github.com/amlang/cakephp_addresshelper/zipball/master)
* Unzip that download.
* Copy the content of resulting folder to `app/`
* Done

Test cases could be found in `Test/Case/View/Helper/AddressHelper.php`  
A sample view could be found in `View/Pages/sample.php`  
[Top](#address--helper-v100-for-cakephp-23)

<a name="enable"/>
## Enable Helper
Embed __AddressHelper__ in your Controller such as any other helpers, too.

```php
	//in a Controller
	public $helper = array('Address');
```

<a name="usage"/>
## Usage

```php
//app/Controller/Samples
 
 public $helpers = array('Address');

```

```php
//app/View/Samples/index.ctp
//Begin a new Address
print $this->Address->beginn('sample');
print $this->Address->name('New York City Samples', array('link' => 'www.nycsamples.com'));
print $this->Address->end();
```


```html
<address id="hcard_sample" class="hcard">
	<div itemprop="name" link="www.nycsamples.com">
		<a href="www.nycsamples.com">
			<span itemprop="name">New York City Samples</span>
		</a>
	</div>
</address>
```
[Top](#address--helper-v100-for-cakephp-23)


<a name="api"/>
## AddressHelper API

`class` AddressHelper

`constant` AddressHelper::SCHEMA_ORG_URL  
   >"http://www.schema.org/"

----
The `$options['format']` key, returns your values in the specified format back.  
i.e.
```php 
$address = array(
			'streetAddress' => '20 Lincoln Center',
			'addressCountry' => 'USA',
			'addressLocality' => 'New York',
			'addressRegion' => 'NY',
			'postalCode' => '10023'
		);
$options = array('format'=>'%s<br />%s,%s %s');
print $this->Address->address( $address, $options );
```

```html
	<!-- 	
			20 Lincoln Center
			USA, New York NY
	-->
	<div itemprop="address" itemscope="itemscope" itemtype="http://www.schema.org/PostalAddress">
		<span itemprop="streetAddress">20 Lincoln Center</span> <br>
		<span itemprop="addressCountry">USA</span> ,<span itemprop="addressLocality">New York</span>
		<span itemprop="addressRegion">NY</span>
	</div>
	
```
----   
AddressHelper::**beginn**( *string* $title, *array* $options = array() );  
Returns
```html
<address id="hcard_title" class="hcard">
```

----

AddressHelper::**end**( *void* );  
Returns
```html
</address>
```

-----

`static` `final` AdressHelper::**getSchema** ( *string* $name='' );  
Returns
```
http://www.schema.org/Name
```

-----

AdressHelper::**name** ( *string* $name, *array* $options = array(), *array* $itemOptions = array());  
Fnc. returns a `name`-Container.  
Keys for `name`-Array could be:
```php
	//For Persons
	$name = array(
		'givenName' => 'Jane',
		'additionalName' => 'MiddleName',
		'familyName' => 'Doh!',
		'honorificPrefix' =>'Dr.',
		'honorificSuffix' =>'M.D.'
		);
	
	//For Organizations
	$name = array(	
		'legalName' => 'MyOrganization Ltd.'
	);
``` 

```html
<div itemprop="name" link="http://example.com">
		<!-- if isset $options['link'] -->
		<!-- <a href="http://example.com">  -->
			<span itemprop="legalName">MyOrganization Ltd.</span>
		<!-- </a> -->
</div>

<!-- or a full name -->

<div itemprop="name" link="http://example.com">
			<span itemprop="honorificPrefix">Prof. Dr.</span>
			<span itemprop="givenName">Alexander</span>
			<span itemprop="additionalName">M.</span>
			<span itemprop="familyName">Lang</span>
			<span itemprop="honorificSuffix">M.D.</span>
</div>

```

----

AdressHelper::**founder** ( *string* $founder, *array* $options = array(), *array* $itemOptions = array());  
Fnc. returns a `founder`-Container
```html
<div itemprop="founder" itemscope="itemscope" itemtype="http://www.schema.org/Person">
	<span itemprop="givenName">Jane</span>
	<span itemprop="additionalName">Middle</span>
	<span itemprop="familyName">Doh!</span>
</div>
```

----
AdressHelper::**address** ( *string* $address, *array* $options = array(), *array* $itemOptions = array());  
Fnc. returns a `address`-Container  
Possible keys for `AddressHelper::address()` are:
```php
	$address = array(
		'streetAddress' => '20 Liconln Center',
		'addressCountry' => 'USA',
		'addressLocality' => 'New York',
		'addressRegion' => 'NY',
	);
```
Returns
```html
<div itemprop="address" itemscope="itemscope" itemtype="http://www.schema.org/PostalAddress">
	<span itemprop="streetAddress">20 Lincoln Center</span>
	<span itemprop="addressCountry">USA</span>
	<span itemprop="addressLocality">New York</span>
	<span itemprop="addressRegion">NY</span>
</div>
```
----

AdressHelper::**contact** ( *string* $address, *array* $options = array(), *array* $itemOptions = array());  
Fnc. returns a `contactPoint`-Container
```html
<!--$options['format'] = '<span class="label">Telefon: </span>%s <br /><span class="label">Fax: </span>%s <br /><span class="label">Email: </span>%s<br /><span class="label">WWW: </span>%s' -->
<div itemprop="contactPoint" itemscope="itemscope" itemtype="http://www.schema.org/ContactPoint">
	<span class="label">Telefon: </span>
	<span itemprop="telephone" class="prop">0126/123456</span>  <br>
	<span class="label">Fax: </span>
	<span itemprop="faxNumber" class="prop">0126/123457</span>  <br>
	<span class="label">Email: </span>
	<a href="mailto:ma.muster@example.com">
		<span itemprop="email" class="prop">ma.muster@example.com</span>
	</a><br>
	<span class="label">WWW: </span>
	<a href="http://www.some-example.de">
		<span itemprop="url" class="prop">http://www.some-example.de</span>
	</a>
</div>
```
----

AdressHelper::**organization** ( *string* $address, *array* $options = array(), *array* $itemOptions = array());  
Fnc. returns a whole `organization`-Container
```php
	
		print $this->Address->organization(array(
				'name' => array(
					'content' => array(
						'legalName' => 'An Organization of MyLand Inc.'
					),
					'options' => array(
						'link' => $this->Html->url($this->request->here, true)
					)
				),
				'founder' => array(
					'content' => array(
						'givenName' => 'Jane',
						'familyName' => 'Doh!'
					)
				),
				'address' => array(
					'content' => array(
						'streetAddress' => 'Mainstreet 1',
						'addressCountry' => 'DE',
						'postalCode' => '12345',
						'addressLocality' => 'ExVille',
						'addressRegion' => 'Region of EV'
					),
					'options' => array(
						'format' => '%s<br />%s-%s,%s %s'
					)
				),
				'contact' => array(
					'content' => array(
						'telephone' => '0126/123456',
						'faxNumber' => '0126/123457',
						'email' => 'ma.muster@example.com'
					),
					'options' => array(
						'format' => 'Telefon: %s <br /> Fax: %s <br /> Email: %s'
					),
					'itemoptions' => array('class' => 'prop')
				)
				), array(
				'class' => 'organization'
		));
		
```
```html
<address id="hcard_my_orga" class="hcard">
	<div itemprop="" class="organization" itemtype="http://www.schema.org/Organization">
		<div itemprop="name" link="http://example.com/pages/siteinfo">
			<a href="http://example.com/pages/siteinfo">
				<span itemprop="legalName">An Organization of MyLand Inc.</span>
			</a>
		</div>
		<div itemprop="founder" itemscope="itemscope" itemtype="http://www.schema.org/Person">
			<span itemprop="givenName">Jane</span>
			<span itemprop="familyName">Doh!</span> 
		</div>
		<div itemprop="address" itemscope="itemscope" itemtype="http://www.schema.org/PostalAddress">
			<span itemprop="streetAddress">Mainstreet 1</span><br>
			<span itemprop="addressCountry">DE</span> -<span itemprop="postalCode">12345</span> ,
			<span itemprop="addressLocality">ExVille</span>
			<span itemprop="addressRegion">Region of EV</span>
		</div>
		<div itemprop="contactPoint" itemscope="itemscope" itemtype="http://www.schema.org/ContactPoint">
			Telefon: <span itemprop="telephone" class="prop">0126/123456</span>  <br>
			Fax: <span itemprop="faxNumber" class="prop">0126/123457</span>  <br>
			Email: <a href="mailto:ma.muster@example.com">
				<span itemprop="email" class="prop">ma.muster@example.com</span>
			</a>
		</div>
	</div>
</address>
```  
[Top](#address--helper-v100-for-cakephp-23)

<a name="license"/>
## License

The MIT License (MIT)

Copyright (c) 2013 Alexander M. Lang

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.  
[Top](#address--helper-v100-for-cakephp-23)