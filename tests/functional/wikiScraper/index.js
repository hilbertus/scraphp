phantomScraper.addJob("https://en.wikipedia.org/wiki/Wikipedia:About",  'about.js');

var scrapedData = {'title': $('title').text(), 'moreData': 42};
phantomScraper.addData(scrapedData);