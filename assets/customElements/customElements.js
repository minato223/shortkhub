import React from "react";
import { defineCustomElements } from "./common/generator";
import HomeMenuItem from "../src/components/layout/navbar/HomeMenuItem";
import ShortCard from "../src/pages/home/ShortCard";
import Screenshot from "../src/pages/screenshot/Screenshot";
// Navigations
defineCustomElements('home-menu-item', <HomeMenuItem />);
//Pages
defineCustomElements('screenshot-page', <Screenshot />);
//Components
defineCustomElements('short-card', <ShortCard />);