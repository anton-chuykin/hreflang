This is an extension to add hreflang to multi-domain project.

So, project has different domans, .com - for English, .de - for German, .it - for Italian, etc.

For each page (cms, product, category, contact us) - need to add hreflang alternatives
* < link rel="alternate" hreflang="en" href="https://website.com">
* < link rel="alternate" hreflang="de" href="https://website.de">
* < link rel="alternate" hreflang="en" href="https://website.it">


So, it's implemented here: added block for each page, then in template hreflang urls are rendered

Hreflang urls have own url provider for each type of page:
For example, load product in every store and use method getProductUrl()

Extension is flexible and already works on several websites