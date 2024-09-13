import * as React from "react";

import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { PlugZap } from "lucide-react";

export interface CardItemProps {
  title: string;
  description: string;
  image: string;
  url: string;
}

export const CardItem = ({ title, description, image, url }: CardItemProps) => {
  return (
    <Card className="w-[350px]">
      <CardHeader className="flex !flex-row !items-start !justify-between">
        <div>
          <img src={image} alt="Image" className="w-10 h-10 rounded-sm mb-3" />
          <CardTitle>{title}</CardTitle>
        </div>
        <a href={url}>
          <Button variant="outline" className="!m-0">
            <PlugZap className="mr-2 h-4 w-4" />
            Connect
          </Button>
        </a>
      </CardHeader>
      <CardContent>
        <CardDescription>
          {description}
        </CardDescription>
      </CardContent>
    </Card>
  );
};
