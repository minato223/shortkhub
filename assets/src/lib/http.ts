export const get = async (url: string) => {
    const response = await fetch(url);
    return response.json();
};

export const post = async (url: string, data: any) => {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });
    return response.json();
};