import { post } from "@/lib/http";
import React, { useCallback, useEffect, useState } from "react";
import * as confetti from "confettis";
import {
  CircleCheck,
  CircleDashed,
  CircleX,
  Loader,
  Loader2Icon,
  Wand,
} from "lucide-react";
import { CardBaseProps } from "../common/interface";
import { Input } from "@/components/ui/input";
import { toast } from "sonner";
import TextareaEditor from "../widgets/editor/TextareaEditor";

interface PostGeneratorProps extends CardBaseProps {
}
const PostGenerator = (
  { icon, title, description, generateUrl }: PostGeneratorProps,
) => {
  const [steps, setSteps] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [editorContent, setEditorContent] = useState<string|undefined>(undefined);
  // const [screenshotsList, setScreenshotsList] = useState<ScreenshotItem[]>(
  //   JSON.parse(screenshots),
  // );
  // const [selectedScreenshot, setSelectedScreenshot] = useState<
  //   ScreenshotItem | null
  // >(null);
  const handleSubmit = useCallback(
    async (e: React.FormEvent<HTMLFormElement>) => {
      e.preventDefault();
      const target = e.target as typeof e.target & {
        url: { value: string };
      };
      setLoading(true);
      try {
        // let result: ScreenshotItem = await post(generateUrl, {
        //   url: target.url.value,
        // });
        // setSelectedScreenshot(result);
        // setScreenshotsList((prev) => {
        //   if (prev.map((item) => item.id).includes(result.id)) {
        //     return prev;
        //   }
        //   confetti.create();
        //   return [...prev, result];
        // });
      } catch (error) {
      }
      setLoading(false);
    },
    [],
  );

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
        if (!isSuccess) {
          toast.error(m);
        }
        if(isFinish && isSuccess){
          setEditorContent(m);
          confetti.create()
        }
      } catch (error) {
      }
    };
    return () => {
    };
  }, []);
  return (
    <div className="grid grid-cols-[2fr,1fr] gap-4 min-h-[calc(100vh-4rem)]">
      <div>
        <div className="max-w-2xl w-full mx-auto">
          <img
            src={icon}
            alt="icon"
            className="w-16 h-16 mt-9 block m-auto mb-3 rounded-sm"
          />
          <h3 className="text-2xl font-medium text-center mb-2">{title}</h3>
          <p className="text-[12px] mb-6 text-center">
            {description}
          </p>
          <form className="relative mb-5" onSubmit={handleSubmit} noValidate>
            <Input
              type="text"
              placeholder="https://url-shooter.com/"
              name="url"
            />
            <button
              className="bg-black p-2 text-gray-300 absolute right-1 top-1/2 -translate-y-1/2"
              disabled={loading}
            >
              {loading
                ? <Loader className="animate-spin text-gray-300 h-4 w-4" />
                : <Wand className="h-4 w-4" />}
            </button>
          </form>
          <TextareaEditor initialValue={editorContent} />
        </div>
      </div>
      <div className="pl-4 border-l border-gray-200">
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
                    className={"font-normal flex gap-2 items-center mb-2 " +
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
        <h3 className="font-medium mb-3 text-lg">History</h3>
        <div className="rounded border bg-card text-card-foreground shadow-sm p-3 cursor-pointer">
          <h3 className="font-normal mb-2 text-blue-800">
            https://url-shooter.com/
          </h3>
          <p className="text-[12px] leading-6">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure neque
            itaque aliquam accusantium dolorum aspernatur nulla quam rerum
            laborum, sed ipsum nam suscipit consequatur ad, ea adipisci, autem
            optio similique?
          </p>
        </div>
      </div>
    </div>
  );
};

export default PostGenerator;
