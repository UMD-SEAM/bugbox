package org.apache.jsp;

import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.jsp.*;
import java.net.*;

public final class displayOpResult_jsp extends org.apache.jasper.runtime.HttpJspBase
    implements org.apache.jasper.runtime.JspSourceDependent {

  private static final JspFactory _jspxFactory = JspFactory.getDefaultFactory();

  private static java.util.List _jspx_dependants;

  private javax.el.ExpressionFactory _el_expressionfactory;
  private org.apache.AnnotationProcessor _jsp_annotationprocessor;

  public Object getDependants() {
    return _jspx_dependants;
  }

  public void _jspInit() {
    _el_expressionfactory = _jspxFactory.getJspApplicationContext(getServletConfig().getServletContext()).getExpressionFactory();
    _jsp_annotationprocessor = (org.apache.AnnotationProcessor) getServletConfig().getServletContext().getAttribute(org.apache.AnnotationProcessor.class.getName());
  }

  public void _jspDestroy() {
  }

  public void _jspService(HttpServletRequest request, HttpServletResponse response)
        throws java.io.IOException, ServletException {

    PageContext pageContext = null;
    HttpSession session = null;
    ServletContext application = null;
    ServletConfig config = null;
    JspWriter out = null;
    Object page = this;
    JspWriter _jspx_out = null;
    PageContext _jspx_page_context = null;


    try {
      response.setContentType("text/html");
      pageContext = _jspxFactory.getPageContext(this, request, response,
      			null, true, 8192, true);
      _jspx_page_context = pageContext;
      application = pageContext.getServletContext();
      config = pageContext.getServletConfig();
      session = pageContext.getSession();
      out = pageContext.getOut();
      _jspx_out = out;

      out.write("\n");
      out.write("<html>\n");
      out.write("<head>\n");
      out.write("   <title>Operation Results</title>\n");
      out.write("   <link rel=\"stylesheet\" href=\"style_master.css\" type=\"text/css\">\n");
      out.write("   <meta http-equiv=\"cache-control\" content=\"no-cache\">\n");
      out.write("</head>\n");
      out.write("<body>\n");
      out.write("\n");
      org.jboss.jmx.adaptor.control.OpResultInfo opResultInfo = null;
      synchronized (request) {
        opResultInfo = (org.jboss.jmx.adaptor.control.OpResultInfo) _jspx_page_context.getAttribute("opResultInfo", PageContext.REQUEST_SCOPE);
        if (opResultInfo == null){
          throw new java.lang.InstantiationException("bean opResultInfo not found within scope");
        }
      }
      out.write("\n");
      out.write("\n");
      out.write("<table width=\"100%\">\n");
      out.write("   <table>\n");
      out.write("      <tr>\n");
      out.write("         <td><img src=\"images/logo.gif\" align=\"left\" border=\"0\" alt=\"JBoss\"></td>\n");
      out.write("         <td valign=\"middle\"><h1>JMX MBean Operation Result <code>");
      out.print( opResultInfo.name);
      out.write("()</code></h1></td>\n");
      out.write("\t  <tr/>\n");
      out.write("   </table>\n");
      out.write("\n");
      out.write("<tr><td>\n");
      out.write("\n");
      out.write("\n");
      out.write("<table cellpadding=\"5\">\n");
      out.write("   <tr>\n");
      out.write("      <td><a href='HtmlAdaptor?action=displayMBeans'>Back to Agent View</a></td>\n");
      out.write("      <td>\n");
      out.write("      <td><a href='HtmlAdaptor?action=inspectMBean&name=");
      out.print( URLEncoder.encode(request.getParameter("name")) );
      out.write("'>Back to MBean View</a></td>\n");
      out.write("      <td>\n");
      out.write("      <td><a href=\n");

	out.print("'HtmlAdaptor?action=invokeOpByName");
	out.print("&name=" + URLEncoder.encode(request.getParameter("name")));
	out.print("&methodName=" + opResultInfo.name );

	for (int i=0; i<opResultInfo.args.length; i++)
    {
		out.print("&argType=" + opResultInfo.signature[i]);
		out.print("&arg" + i + "=" + opResultInfo.args[i]);
	}

	out.println("'>Reinvoke MBean Operation");

      out.write("\n");
      out.write("\t  </a></td>\n");
      out.write("   </tr>\n");
      out.write("</table>\n");
      out.write("\n");
      out.write("\n");
      out.write("<hr>\n");
      out.write("   <span class='OpResult'>\n");

   if( opResultInfo.result == null )
   {

      out.write("\n");
      out.write("   Operation completed successfully without a return value.\n");

   }
   else
   {
      String opResultString = opResultInfo.result.toString();
      boolean hasPreTag = opResultString.startsWith("<pre>");
      if( hasPreTag == false )
         out.println("<pre>");
      out.println(opResultString);
      if( hasPreTag == false )
         out.println("</pre>");
   }

      out.write("\n");
      out.write("   </span>\n");
      out.write("</td></tr>\n");
      out.write("</table>\n");
      out.write("</body>\n");
      out.write("</html>\n");
    } catch (Throwable t) {
      if (!(t instanceof SkipPageException)){
        out = _jspx_out;
        if (out != null && out.getBufferSize() != 0)
          try { out.clearBuffer(); } catch (java.io.IOException e) {}
        if (_jspx_page_context != null) _jspx_page_context.handlePageException(t);
      }
    } finally {
      _jspxFactory.releasePageContext(_jspx_page_context);
    }
  }
}
