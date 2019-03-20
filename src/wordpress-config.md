Wordpress admin config changes
==============================

- Appearance > Customise > Additional RSS: add the following to turn off display of "view
  full size photo

  ````
  /* Jetpack Carousel */

  .jp-carousel-image-meta {
  display: none;
  }
  ````
- General Settings
  - Titel: Inconsequential
  - Tagline: A speck at the edge of the internet universe
  - Email Address: localwordpress@kaptech.co.uk
  - New User Default Role: Subscriber
  - Date format: j M Y
  - Time format: H:i
- Writing
  - Default post category: Uncategorised
  - Default post format: Standard
- Reading
  - Your homepage displays: your latest posts
  - Blog pages show at most: 10 posts
  - Syndication feeds show the most recent: 10 items
  - For each article in a feed, show: Summary  
- Discussion
  - Default article settings: attempt to notify any blogs; allow link notification; allow
    people to post comments on new articles
  - Other comment settings: comment author must fill out name and email; show comments
    cookies opt-in checkbox; enable threaded commnents 5 levels deep;
  - Comments should be displayed with the newer comments at the top of each page
  - email me when: anyone posts a comment; a comment is held for moderation
  - before a comment appears: comment author must have a previously approved comment
  - content moderation: hold a comment in the queue if it contains 2 or more links
    (probably spam)
  - Avator: show avatars
  - Max rating: G
  - Default avatar: Mystery Person
- Media Settings
  - Thumbnail size: 150 x 150
  - Medium size: 500 x 500
  - Large size: 1024 x 1024
  - Uploading: organise into month/year based folders 
- Permalink
  - Common settings: Day and name
- Privacy
  - Select page: Privacy Policy


Jetpack config
===============

Settings
---------

Performance:
- Enable lazy loading for images
- Display images in a full-screen carousel gallery

Sharing:
- Add sharing buttons to your posts:
  - Facebook
  - Twitter
  - WhatsApp
  - Reddit
- Services hidden by share button:
  - Pinterest
  - LinkedIn
  - Telegram
  - Pocket
  - Print

Plugins
=========

- Akismet anti-spam
- Jetpack
- JSON Basic Authentication
- PK Album widget
- PK Photo Album
- Ultimate Addons for Gutenberg (not used)

  