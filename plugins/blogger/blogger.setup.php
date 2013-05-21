<?php
/* ====================
[BEGIN_COT_EXT]
Code=blogger
Name=Blogger
Description=WebLogs plugin for Cotonti CMF. Users can create their blogs.
Version=1.5.0
Date=03 January 2013
Author=Alex
Copyright=&copy; 2010-2013 http://portal30.ru (Portal30 studio)
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=A
Requires_modules=page,users
Recommends_plugins=attach2
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
rootCat=10:string::blogs:Root category
subCatsOn=15:radio::1:Can Blogger make subcategories in his blog?
themeSelectOn=25:radio::1:Can Blogger select theme for his blog?
turnedOffThemes=30:string:::Turned off skins
thumbsPerLine=35:select:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50,60,70,80,90,100:3:Skin thumbs per line
thumbsLinePerPage=40:select:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50,60,70,80,90,100:5:Skin thumb lines per page
notifyAdminNewBlog=45:radio::1:New blog admin notify?
notifyAdminNewPage=50:radio::1:New page admin notify?
canDelNotEmptyCat=55:radio::1:Can users delete not empty categories?
rssToHeader=70:radio::1:Add blog rss in site header?
[END_COT_EXT_CONFIG]
==================== */

/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');
