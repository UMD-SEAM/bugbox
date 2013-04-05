
class HTTPMyDebugProcessor(urllib2.BaseHandler):
    """ Track HTTP requests and responses with this custom handler.
    Be sure to add it last in your build_opener call, or use:
        handler_order = 900 """
    def __init__(self, httpout=sys.stdout):
        self.httpout = httpout

    def http_request(self, request):
        if __debug__:
            host, full_url = request.get_host(), request.get_full_url()
            url_path = full_url[full_url.find(host) + len(host):]
            self.httpout.write("%s\n" % request.get_full_url())
            self.httpout.write('\n')
            self.httpout.write("%s %s\n" % (request.get_method(), url_path))

            for header in request.header_items():
                self.httpout.write("%s: %s\n" % header[:])

            self.httpout.write('\n')

        return request

    def http_response(self, request, response):
        if __debug__:
            code, msg, hdrs = response.code, response.msg, response.info()
            self.httpout.write("HTTP/1.x %s %s\n" % (code, msg))
            self.httpout.write(str(hdrs))

        return response



headers = {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
           'Referer' : 'http://128.8.127.115/wordpress/wp-admin/edit.php',
           'X-Requested-With' : 'XMLHttpRequest',
           'Accept' : 'text/html, */*; q=0.01',
           'Host' : '129.8.127.115',
           'User-Agent' : 'Mozilla/5.0 (X11; Linux i686; rv:13.0) Gecko/20100101 Firefox/13.0.1 Iceweasel/13.0.1'}

#request = urllib2.Request(url1, urllib.urlencode(form_data), headers)
  
