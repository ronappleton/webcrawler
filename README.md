# Web Crawler

Simple web crawler for retrieving site links

This web crawler package is a simple package, designed for taking websites and extracting
the files it can find from the html that the site provides.

It is restricted to the source domain by default, can be altered using the restrict_domain option
of the crawl method.

It was built for handling known self linking sites, although I will add controls to prevent
external crawling when required.

It is simple to use, and solves some of the issues other people have had trying to build simple
crawlers.

## Supported

 - Scanning and retrieving web page.
 - Reading and pulling out all links in web page.
 - Deducing if link is to another directory or to a file.
 - Storing file and directory location (web location)
 - Handles relative and non relative urls
 - Times crawls
 - Provides minimal count statistic
 - Exports data collected as array
 - Exports data collected as Json
 
## Warning

Use this at your own risk, please don't crawl sites of people that are not expecting it, the risk is all yours

