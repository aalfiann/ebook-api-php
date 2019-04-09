# EbookAPI

[![Version](https://img.shields.io/badge/stable-1.0.0-green.svg)](https://github.com/aalfiann/ebook-api-php)
[![Total Downloads](https://poser.pugx.org/aalfiann/ebook-api-php/downloads)](https://packagist.org/packages/aalfiann/ebook-api-php)
[![License](https://poser.pugx.org/aalfiann/ebook-api-php/license)](https://github.com/aalfiann/ebook-api-php/blob/HEAD/LICENSE.md)

A PHP wrapper class to get data ebook from Google Book and GoodReads API.

## Installation

Install this package via [Composer](https://getcomposer.org/).
```
composer require "aalfiann/ebook-api-php:^1.0"
```


## Usage to use Google Book API

This is the basic to search books.
```php
use aalfiann\EbookAPI\GoogleBook;
require_once ('vendor/autoload.php');

header('Content-Type: application/json');
$ebook = new GoogleBook('YOUR_GOOGLE_API_KEY');

// Search book
echo $ebook->search('vue js')
    
    // you can add filter to search only free-ebooks
    ->filter('free-ebooks')
    
    // you can add projection 'lite'. This includes only a subject of volume and access metadata.
    ->projection('lite')
    
    // you can add printType 'books'. This will return just books.
    ->printType('books')

    // you can add langRestrict 'en'. This will return books with english only.
    ->langRestrict('en')

    // you can add download 'epub'. Currently only support epub.
    ->download('epub')

    // you can add orderBy 'newest'. This will order books start from newest.
    ->orderBy('newest')

    // you can add startIndex 0. First page always start from 0.
    ->startIndex(0)

    // you can add maxResults 10. This will return only 10 item of books. Max value is 40.
    ->maxResults(10)

    // send request to Google Book API
    ->send()

    // get the response from Google Book API
    ->getResponse();
```

### Get Books start with spesific parameter.  

Get book by title  
```php
// Get book by title name
$ebook->title('steve jobs')->maxResults(10);

// Get book by title name 'steve jobs' but only return which is having 'Biography' word in title
$ebook->title('steve jobs','Biography')->maxResults(10);
```

Get book by author
```php
// Get book by author name
$ebook->author('keyes')->maxResults(10);

// Get book by author name 'keyes' but only return which is having 'flowers' word in title
$ebook->author('keyes','flowers')->maxResults(10);
```

Get book by subject name
```php
// Get book by subject name
$ebook->subject('Fiction')->maxResults(10);

// Get book by subject name 'Fiction' but only return which is having 'flowers' word in title
$ebook->subject('Fiction','flowers')->maxResults(10);
```

Get book by publisher name
```php
// Get book by publisher name
$ebook->publisher('OUP Oxford')->maxResults(10);

// Get book by publisher name 'OUP Oxford' but only return which is having 'Law' word in title
$ebook->publisher('OUP Oxford','Law')->maxResults(10);
```

Get book by ID
```php
// Get book by ISBN
$ebook->isbn('ISBN_ID');

// Get book by LCCN
$ebook->lccn('LCCN_ID');

// Get book by OCLC
$ebook->oclc('OCLC_ID');
```

### Get Bookshelves

```php
// Get bookshelves from user id '109862556655476830073' 
echo $ebook->bookshelves('109862556655476830073')
    
    // you can choose the shelf. For example only shelf with id '1001'
    ->shelf('1001')

    // if you want to show the items inside shelf
    ->retrieve()
    
    // send request to Google Book API
    ->send()
    
    // get response from Google Book API
    ->getResponse();
```

If you want to see the response
```php
echo $ebook->send()->getResponse();
```

If you want to count the result items
```php
echo $ebook->send()->count();
```

If you want to see the debug response
```php
echo $ebook->send(false)->getResponse();
```

## Usage to use GoodReads API
GoodReads API still in development process and unstable (code will change in the future).  
We already stop to develop GoodReads API wrapper at this moment, maybe we will continue this later.

This is how to use with GoodReads API
```php
use aaliann\EbookAPI\GoodReads;
$ebook = new GoodReads('YOUR_GOODREADS_API_KEY');

// Get book by ISBN
echo $ebook->getBookByISBN('9781451648546');

// Get book by title
echo $ebook->getBookByTitle('vuejs');
```

**Note:**
- This libraries does not include with cache. You should cache the results, because Google has **LIMIT RATE** and will block your IP address server if too many request.