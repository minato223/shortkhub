import axios, { AxiosError, AxiosResponse } from "axios";
import { toast } from "sonner";

const handleRequest = async <T>(request: Promise<AxiosResponse<T>>) => {
  try {
    const response = await request;
    return response.data;
  } catch (error) {
    if (error instanceof AxiosError) {
      toast.error(error.response?.data?.message || error.message);
    } else {
      toast.error("An unexpected error occurred");
    }
    throw error;
  }
};

export const get = async <T>(url: string): Promise<T> => {
  return handleRequest(axios.get<T>(url));
};

export const post = async <T>(url: string, data: any): Promise<T> => {
  return handleRequest(axios.post<T>(url, data));
};
