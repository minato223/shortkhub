import React from "react";
import { defineCustomElements } from "./common/generator";
import HomeMenuItem from "../src/components/layout/navbar/HomeMenuItem";
import ShortCard from "../src/pages/home/ShortCard";
import Screenshot from "../src/pages/screenshot/Screenshot";
import PostGenerator from "../src/pages/postGenerator/PostGenerator";
import AppGenerator from "../src/pages/appGenerator/AppGenerator";
import AppGeneratorDetail from "../src/pages/appGenerator/AppGeneratorDetail";
import { Toast } from "../src/components/layout/widgets/toast/toast";
// Navigations
defineCustomElements('home-menu-item', <HomeMenuItem />);
//Pages
defineCustomElements('screenshot-page', <Screenshot />);
defineCustomElements('post-generator-page', <PostGenerator />);
defineCustomElements('app-generator-page', <AppGenerator />);
defineCustomElements('app-generator-detail-page', <AppGeneratorDetail />);
//Components
defineCustomElements('short-card', <ShortCard />);
defineCustomElements('toaster-widget', <Toast />);