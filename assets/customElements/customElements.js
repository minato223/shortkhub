import React from "react";
import HomeMenuItem from "../src/components/layout/navbar/HomeMenuItem";
import ShortCard from "../src/pages/home/ShortCard";
import { defineCustomElements } from "./common/generator";
// Navigations
defineCustomElements('home-menu-item', <HomeMenuItem />);
//Pages
// defineCustomElements('home-page', <HomePage />);
//Components
defineCustomElements('short-card', <ShortCard />);