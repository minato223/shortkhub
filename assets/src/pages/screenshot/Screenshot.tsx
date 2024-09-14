import { Input } from "@/components/ui/input";
import { post } from "@/lib/http";
import { Loader, Wand } from "lucide-react";
import React, { useCallback, useState } from "react";
import { ScreenshotItem } from "./model/ScreenshotItem";

interface ScreenshotProps {
  icon: string;
  title: string;
  description: string;
  generateUrl: string;
  screenshots: string;
}
const formatDate = (date: string) => {
  const dateObject = new Date(date);
  const options: Intl.DateTimeFormatOptions = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    second: "numeric",
  };
  return dateObject.toLocaleString("fr-FR", options);
};
const Screenshot = (
  { icon, title, description, generateUrl, screenshots }: ScreenshotProps,
) => {
  const [loading, setLoading] = useState(false);
  const [screenshotsList, setScreenshotsList] = useState<ScreenshotItem[]>(
    JSON.parse(screenshots),
  );
  const [selectedScreenshot, setSelectedScreenshot] = useState<
    ScreenshotItem | null
  >(null);
  const handleSubmit = useCallback(
    async (e: React.FormEvent<HTMLFormElement>) => {
      e.preventDefault();
      const target = e.target as typeof e.target & {
        url: { value: string };
      };
      setLoading(true);
      try {
        let result: ScreenshotItem = await post(generateUrl, {
          url: target.url.value,
        });
        setSelectedScreenshot(result);
        setScreenshotsList((prev) => [...prev, result]);
      } catch (error) {
      }
      setLoading(false);
    },
    [],
  );
  return (
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
          <button className="bg-black p-2 text-gray-300 absolute right-1 top-1/2 -translate-y-1/2">
            {loading
              ? <Loader className="animate-spin text-gray-300 h-4 w-4" />
              : <Wand className="h-4 w-4" />}
          </button>
        </form>
        {selectedScreenshot && (
          <a href={selectedScreenshot.path} target="_blank" rel="noreferrer">
            <div className="relative h-[500px] overflow-hidden outline">
              <img
                src={selectedScreenshot.path}
                alt="screenshot"
                className="block m-auto"
              />
              <div
              className="absolute top-0 left-0 w-full h-full bg-gradient-to-t from-gray-700 to-transparent from-0 to-50%"
              >
              </div>
            </div>
          </a>
        )}
      </div>
      <div>
        <h4 className="text-xl font-medium mb-1 mt-10">All Screenshots</h4>
        <p className="text-[12px] mb-6">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat quas
          similique voluptatibus consequatur placeat.
        </p>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {screenshotsList.map((screenshot) => (
          <div
            key={screenshot.id}
            className="border bg-card text-card-foreground shadow-sm p-1"
          >
            <a href={screenshot.path} target="_blank" rel="noreferrer">
              <div className="relative h-[200px] overflow-hidden mb-2">
                <img
                  src={screenshot.path}
                  alt="screenshot"
                  className="mb-3 block m-auto min-h-full"
                />
              </div>
            </a>
            <h3 className="font-medium text-wrap break-words mb-1">{screenshot.name}</h3>
            <h3 className="font-medium text-[12px] text-wrap break-words">Date de création : {formatDate(screenshot.createdAt)}</h3>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Screenshot;
