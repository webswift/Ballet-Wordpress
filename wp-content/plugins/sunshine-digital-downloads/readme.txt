=== Sunshine Photo Cart - Digital Downloads ===
Author URI: https://www.sunshinephotocart.com
Plugin URI: https://www.sunshinephotocart.com/addon/digital-downloads
Contributors: sunshinephotocart

Add-on for Sunshine Photo Cart - Allows clients to order and download digital negatives

== Changelog ==

= 2.2.3 =
* Fix - Bulk add products, not saving "downloadable" option

= 2.2.2 =
* Update - Better notification when not logged in and trying to download from email link
* Fix - Allow purchase entire gallery downloads for more than 1 gallery per checkout

= 2.2.1 =
* Fix - More accurate download count, specific to order or free download

= 2.2 =
* Update - Work with new guest checkout
* Add - Show download count in order details in admin

= 2.1.15 =
* Fix - Saving empty size values defaulted to 0 when it should stay empty

= 2.1.14 =
* Change - Move .htaccess file to sunshine image folder

= 2.1.13 =
* Fix - Downloading single file in order failed if image was full size

= 2.1.12 =
* Change - Change to how it helps proper billing/shipping data be saved when only digital downloads are in cart
* Fix - Alternate download of entire gallery had bug with image sizes

= 2.1.11 =
* Change - Another new method of htaccess for full res image prevention

= 2.1.10 =
* Revert - New htaccess method was too overreaching, reverting back to previous method

= 2.1.9 =
* Change - New method of htaccess for full res image prevention
* Fix - Issue when trying to download single image and only one size

= 2.1.8 =
* Change - Gallery download file name is the gallery slug
* Fix - Better checkout page adjustments when digital download only

= 2.1.7 =
* Fix - When only single download is a non-full sized image, deliver proper file download

= 2.1.6 =
* Change - Only full size, no low res and no print release, send just the one .jpg file and not a zip

= 2.1.5 =
* Fix - Giving full res in some instances where it should only give set size based on product settings
* Fix - Don't show link to purchase entire gallery download if it is already in cart

= 2.1.4 =
* Fix - Orders with only digital downloads prematurely being marked as completed with PayPal

= 2.1.3 =
* Fix - Not adding default low res image with and height on update/install

= 2.1.2 =
* Fix - Issues with Download All link in order/invoice page for Entire Gallery Download products

= 2.1.1 =
* Fix - Removed unnecessary file

= 2.1 =
* Add - Integration with WP Offload S3 so you can store images on an Amazon S3 account

= 2.0.3 =
* Fix - Hide shipping selection notice in cart/checkout overview when only digital downloads are in cart
* Fix - Specific setting configurations caused issues generating zip file

= 2.0.2 =
* Add - Notice that if watermark is selected, the low res download file will also have watermark on it
* Fix - Showing upgrade notice when there are no galleries to upgrade

= 2.0.1 =
* Fix - generated .htaccess for protecting direct image access updated to work on more types of domains

= 2.0 =
* Add - Downloadable products can now have set sizes
* Update - Only a single upload, no more low and high res file uploads
* Update - Newer version of the zip file generation class
* Add - Creates .htaccess file for more secure file protection

= 1.1.5 =
* Fix - Save FTP folder fix actually caused more issues, reverted. Will be non-issue in 2.0.

= 1.1.4 =
* Fix - Save FTP folder selection even if it is set to null

= 1.1.3 =
* Fix - Not including low res images when downloading entire gallery

= 1.1.2 =
* Fix - Adding multiple gallery downloads to cart would increase quantity instead of adding new cart item
* Fix - Don't allow the same gallery download to be added to cart twice

= 1.1.1 =
* Change - Compatible with future bulk galleries add-on
* Fix - Remove some debug code that was displayed in some instances

= 1.1 =
* Change - Shipping information is not requested at checkout if only digital downloads are in cart

= 1.0.1 =
* Fix - Not saving proper download folder when browser upload at initial gallery creation

= 1.0 =
* Converted to add-on plugin
* Add - New filter for allowed file type extensions
