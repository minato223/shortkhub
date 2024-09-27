import React, { useState } from "react";
import { CardBaseProps } from "../common/interface";
import Empty from "../widgets/Empty";
import { CreateForm } from "./form/CreateForm";
import { AppProjectItem } from "./model/AppProjectItem";
import { formatDate } from "../screenshot/Screenshot";
import { Button } from "@/components/ui/button";
import { GearIcon } from "@radix-ui/react-icons";
interface AppGeneratorProps extends CardBaseProps {
  createUrl: string;
  projects: string;
}
const AppGenerator = (
  { icon, title, description, createUrl, projects }: AppGeneratorProps,
) => {
  const [projectsList, setProjectsList] = useState<AppProjectItem[]>(
    JSON.parse(projects),
  );
  return (
    <div className="min-h-[calc(100vh-15rem)]">
      <div className="flex items-start gap-4 border-b border-gray-200 py-4 px-4 mb-3 justify-between">
        <div className="flex items-start gap-4">
          <img
            src={icon}
            alt="icon"
            className="w-16 h-16 block rounded-sm"
          />
          <div>
            <h3 className="text-2xl font-medium mb-2">{title}</h3>
            <p className="text-[12px] text-gray-400">
              {description}
            </p>
          </div>
        </div>
        <CreateForm createUrl={createUrl} />
      </div>
      {projectsList.length > 0 &&
          (
            <>
              <h3 className="font-medium mb-3 text-lg">All Projects</h3>
              <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                {projectsList.map((project) => (
                  <div
                    key={project.name}
                    className="border bg-card text-card-foreground shadow-sm py-4 px-5 cursor-pointer"
                  >
                    <div className="mb-3 flex items-center gap-3">
                      <img
                        src={project.icon}
                        alt="screenshot"
                        className="block h-11 w-11 rounded-[2px]"
                      />
                      <div>
                        <h3 className="font-medium text-wrap break-words mb-1">
                          {project.name}
                        </h3>
                        <h4 className="font-medium text-[12px] text-wrap text-blue-800">
                          {project.url}
                        </h4>
                      </div>
                    </div>
                    <p className="text-[12px] text-gray-400">
                      {project.description}
                    </p>
                    <div className="mt-3 pt-1 flex items-center justify-between">
                      <a href={project.redirect}>
                        <Button variant="outline" className="!m-0">
                          <GearIcon className="mr-2 h-4 w-4" />
                          Manage
                        </Button>
                      </a>
                    </div>
                  </div>
                ))}
              </div>
            </>
          ) ||
        (
          <div className="my-[150px]">
            <Empty />
          </div>
        )}
    </div>
  );
};

export default AppGenerator;
