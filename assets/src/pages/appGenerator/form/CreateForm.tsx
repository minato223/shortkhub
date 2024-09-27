import React from "react";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet";
import { Button } from "@/components/ui/button";
import { FolderOpen } from "lucide-react";
import {
  Form,
  FormControl,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";

import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { z } from "zod";
import { Textarea } from "@/components/ui/textarea";
import { post } from "@/lib/http";

const MAX_FILE_SIZE = 1000000;

const checkFileType = (file?: File | null) => {
  if (file?.name) {
    const fileType = file.name.split(".").pop();
    if (fileType === "png" || fileType === "jpg" || fileType === "jpeg") {
      return true;
    }
  }
  return false;
};

// Validation Zod avec fichier optionnel
const formSchema = z.object({
  name: z.string().min(2, {
    message: "Project name must be at least 2 characters.",
  }),
  url: z.string().url("Provide a valid URL"),
  description: z.string().min(2, {
    message: "Please, provide a description for the project.",
  }),
  // Nous ne validons pas directement le fichier ici, mais dans onSubmit
  icon: z.instanceof(FileList).optional(),
});

interface CreateFormProps {
  createUrl: string;
}
export const CreateForm = ({ createUrl }: CreateFormProps) => {
  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      name: "",
      url: "",
      description: "",
    },
  });

  // Fonction pour envoyer les données via Fetch
  async function sendRequest(data: FormData) {
    try {
      const {data: result} = await post<any>(createUrl, data);
      window.location.href = result.redirect;
    } catch (error) {
      console.error("Error submitting form:", error);
      alert("Failed to submit form");
    }
  }

  // Gestion de la soumission du formulaire
  function onSubmit(values: z.infer<typeof formSchema>) {
    const file = values.icon?.[0]; // Nous récupérons le premier fichier si présent

    // Vérification du fichier avant de continuer
    if (file) {
      if (file.size > MAX_FILE_SIZE) {
        alert("File is too large. Maximum size is 1MB.");
        return;
      }
      if (!checkFileType(file)) {
        alert("Invalid file type. Only PNG, JPG, and JPEG are allowed.");
        return;
      }
    }

    // Création d'un FormData pour envoyer les fichiers et autres données
    const formData = new FormData();
    formData.append("name", values.name);
    formData.append("url", values.url);
    formData.append("description", values.description);

    if (file) {
      formData.append("icon", file); // Ajout du fichier si présent
    }

    // Envoi des données au serveur
    sendRequest(formData);
  }

  return (
    <Sheet>
      <SheetTrigger asChild>
        <Button variant="outline" className="!m-0">
          <FolderOpen className="mr-2 h-4 w-4" />
          Add new project
        </Button>
      </SheetTrigger>
      <SheetContent>
        <SheetHeader className="mb-4">
          <SheetTitle>New Project</SheetTitle>
          <SheetDescription>
            Create a new project by filling the form below.
          </SheetDescription>
        </SheetHeader>
        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-8">
            {/* Champ nom */}
            <FormItem>
              <FormLabel>Name</FormLabel>
              <FormControl>
                <Input
                  placeholder="Ex: App Generator"
                  {...form.register("name")}
                />
              </FormControl>
              <FormMessage>{form.formState.errors.name?.message}</FormMessage>
            </FormItem>

            {/* Champ URL */}
            <FormItem>
              <FormLabel>Url</FormLabel>
              <FormControl>
                <Input
                  placeholder="Ex: https://example.com"
                  {...form.register("url")}
                />
              </FormControl>
              <FormMessage>{form.formState.errors.url?.message}</FormMessage>
            </FormItem>

            {/* Champ Fichier */}
            <FormItem>
              <FormLabel>App Logo</FormLabel>
              <FormControl>
                <Input
                  type="file"
                  {// On utilise register pour capturer le fichier
                  ...form.register("icon")}
                />
              </FormControl>
              <FormMessage>{form.formState.errors.icon?.message}</FormMessage>
            </FormItem>

            {/* Champ description */}
            <FormItem>
              <FormLabel>Description</FormLabel>
              <FormControl>
                <Textarea placeholder="..." {...form.register("description")} />
              </FormControl>
              <FormMessage>
                {form.formState.errors.description?.message}
              </FormMessage>
            </FormItem>

            <Button type="submit">Submit</Button>
          </form>
        </Form>
      </SheetContent>
    </Sheet>
  );
};
