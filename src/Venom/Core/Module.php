<?php


namespace Venom\Core;

interface Module
{
    const NAME = "name";
    const AUTHOR = "author";
    const SECURE = "secure";
    const ROUTE = "routes";
    const ADMIN_ROUTE = "adminRoutes";
    const DESC = "description";
    const TEMPLATES = "templates";
    const ADMIN_TEMPLATES = "adminTemplates";
    const CONTROLLER = "controllers";
    const TEMPLATE_PATH = "tplPath";
    const ACTIVE = "isActive";
}