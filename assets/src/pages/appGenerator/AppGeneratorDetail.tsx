import { post } from "@/lib/http";
import React, { useCallback, useEffect, useRef, useState } from "react";
import * as confetti from "confettis";
import {
  CircleCheck,
  CircleDashed,
  CircleX,
  DownloadIcon,
  FolderOpen,
  Loader,
  Loader2Icon,
  Wand,
} from "lucide-react";
import { CardBaseProps } from "../common/interface";
import { Input } from "@/components/ui/input";
import { toast } from "sonner";
import TextareaEditor from "../widgets/editor/TextareaEditor";
import { Button } from "@/components/ui/button";
import Empty from "../widgets/Empty";
import { CreateForm } from "./form/CreateForm";
import { AppProjectItem } from "./model/AppProjectItem";
import MaterialSymbolsAndroid from "@/assets/svg/MaterialSymbolsAndroid";
// import mockup from "@/assets/images/mockup.png";
interface AppGeneratorDetailProps extends CardBaseProps {
  generateUrl: string;
  project: string;
  downloadUrl: string;
}
const AppGeneratorDetail = (
  { icon, title, description, generateUrl, project, downloadUrl }: AppGeneratorDetailProps,
) => {
  const [steps, setSteps] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [topic, setTopic] = useState<string | undefined>(undefined);
  const projectData: AppProjectItem = JSON.parse(project);
  const [download, setDownload] = useState(false);

  const buildApk = useCallback(async () => {
    setLoading(true);
    try {
      const json = {
        "uuid": projectData.uuid,
      };
      const response = await post(generateUrl, json);
    } catch (error) {
      console.error(error);
    }
    setLoading(false);
  }, []);

  useEffect(() => {
    const progressTopic = "/progress";
    const pingTopic = "/ping";
    const hubUrl = "http://localhost:8088/.well-known/mercure";
    const url = new URL(`${hubUrl}`);
    url.searchParams.append("topic", progressTopic);
    url.searchParams.append("topic", pingTopic);
    const eventSource = new EventSource(url);
    eventSource.onmessage = (e) => {
      const { message } = JSON.parse(e.data);
      try {
        const { steps, isSuccess, message: m, isFinish } = JSON.parse(message);
        setSteps(steps);
        console.log(steps);
        
        if (!isSuccess) {
          toast.error(m);
        }
        if (isFinish && isSuccess) {
          confetti.create();
          setDownload(true);
        }
      } catch (error) {
      }
    };
    return () => {
    };
  }, []);

  return (
    <div className="min-h-[calc(100vh-15rem)]">
      <div className="flex gap-4 mb-4">
        <img
          src={projectData.icon}
          alt="icon"
          className="w-16 h-16 block rounded-sm"
        />
        <div>
          <h3 className="text-xl font-medium mb-2">{projectData.name}</h3>
          <a href={projectData.url} className="text-blue-800 font-medium">
            {projectData.url}
          </a>
        </div>
      </div>
      <p className="text-[12px] text-gray-400 border-b border-gray-200 pb-2">
        {projectData.description}
      </p>
      <div className="grid grid-cols-[2fr,1fr] gap-4">
        <div className="p-5 mt-10">
          <div className="max-w-[400px] m-auto">
            <div className="relative h-[700px] mb-8">
              <iframe
                src={projectData.url}
                width="100%"
                className="w-full h-full rounded-[3rem] z-10 relative px-1"
                title="app-generator-iframe"
              >
              </iframe>
              <img
                src="../assets/mockup.png"
                alt="mockup"
                className="absolute top-0 left-0 w-full h-full scale-[1.04]"
              />
            </div>
            <div className="flex items-center gap-2 mt-4">
              <Button
                onClick={buildApk}
                disabled={loading}
                variant="outline"
                className="!m-0 w-full"
              >
                <MaterialSymbolsAndroid className="h-4 w-4 mr-3" />
                Generate .apk
              </Button>
              <Button
                onClick={buildApk}
                disabled={loading}
                variant="outline"
                className="!m-0 w-full"
              >
                <MaterialSymbolsAndroid className="h-4 w-4 mr-3" />
                Generate .aab
              </Button>
            </div>
          </div>
        </div>
        <div className="text-xl border-l border-gray-200 p-4">
          <h3 className="font-medium mb-4">App Version</h3>
          <div className="rounded border bg-card text-card-foreground shadow-sm p-3 ">
            <h3 className="font-normal mb-2">
              v0.0.1
            </h3>
            <div className="flex items-center gap-2 mb-3">
              <span className="text-[12px] leading-6 font-normal">
                platform
              </span>
              <span className="bg-slate-200 px-3 py-1 rounded-sm flex items-center gap-2 text-[12px] leading-6 font-normal">
                <MaterialSymbolsAndroid className="h-4 w-4" />Android
              </span>
            </div>
            <div className="flex items-center gap-2 mb-3">
              <span className="text-[12px] leading-6 font-normal">
                type
              </span>
              <span className="bg-slate-200 px-3 py-1 rounded-sm flex items-center gap-2 text-[12px] leading-6 font-normal">
                .apk
              </span>
            </div>
            <Button variant="outline" className="!m-0 w-full">
              <DownloadIcon className="mr-2 h-4 w-4" />
              Download
            </Button>
          </div>
          {steps.length > 0 && (
            <div className="mb-5">
              <div className="rounded border bg-card text-card-foreground shadow-sm p-3 cursor-pointer mt-4">
                {steps.map((step, index) => {
                  let textColor = step["state"] === "success"
                    ? "text-green-500"
                    : step["state"] === "error"
                    ? "text-red-500"
                    : step["state"] === "wainting"
                    ? "text-gray-300"
                    : "";
                  return (
                    <h3
                      key={index}
                      className={"font-normal flex gap-2 items-center mb-2 text-[14px] leading-6 " +
                        textColor}
                    >
                      {step["state"] === "waiting" && (
                        <CircleDashed size={17} className="text-gray-300" />
                      )}
                      {step["state"] === "processing" && (
                        <Loader size={17} className="animate-spin" />
                      )}
                      {step["state"] === "success" && (
                        <CircleCheck size={17} className="text-green-500" />
                      )}
                      {step["state"] === "error" && (
                        <CircleX size={17} className="text-red-500" />
                      )}
                      {step["message"]}
                    </h3>
                  );
                })}
              </div>
            </div>
          )}
          {download && <a href={downloadUrl} target="_blank" rel="noreferrer">
            <Button variant="default" className="!m-0 w-full">
              <DownloadIcon className="mr-2 h-4 w-4" />
              Download Now
            </Button>
          </a>}
        </div>
      </div>
    </div>
  );
};

export default AppGeneratorDetail;
