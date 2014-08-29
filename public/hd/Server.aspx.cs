﻿using System;
using System.Collections.Generic;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

public partial class Server : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!Page.IsPostBack)
        {
            if (Request["id"] != null && !string.IsNullOrEmpty(Request["id"].ToString()))
            {
              //启用该句会引发ajax超时异常
             //   System.Threading.Thread.Sleep(3000);
                Response.Write(GetData(Request["id"].ToString()));
            }
        }
    }

    protected string GetData(string id)
    {
        string str = string.Empty;
        switch (id)
        {
            case "1":
                str += "1111";
                break;
            case "2":
                str += "2222";
                break;
            case "3":
                str += "3333";
                break;
            default:
                str += "4444";
                break;
        }
        return str;
    }
}