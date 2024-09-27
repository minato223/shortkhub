import { Button } from "@/components/ui/button";
import { Copy, Plus } from "lucide-react";
import React, { useEffect, useState } from "react";

interface TextareaEditorProps {
  initialValue?: string;
}
const TextareaEditor = ({ initialValue }: TextareaEditorProps) => {    
  const [editorContent, setEditorContent] = useState<string|undefined>(initialValue);

  const share = () => {
    const data: ShareData = {
      text: editorContent,
    };
    try {
      navigator.share(data);
    } catch (error) {
    }
  };

  const addToClipboard = () => {
    if (!editorContent) {
      return;
    }
    navigator.clipboard.writeText(editorContent);
  };

  return (
    <>
      <div className="bg-slate-50 mb-3">
        {JSON.stringify(editorContent)}
      </div>
      <Button
        variant="outline"
        className="!m-0 w-full"
        onClick={addToClipboard}
      >
        <Copy className="mr-2 h-4 w-4" />
        Add to clipboard
      </Button>
    </>
  );
};

export default TextareaEditor;
