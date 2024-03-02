import { environment } from "../../../environments/environment";

const {apiUrl} = environment;

export const API_URLS = {
    // Study Programme
    getAllStudyProgrammes: () => `${apiUrl}/study-programmes`,
    getStudyProgramme: (id: string) => `${apiUrl}/study-programmes/${id}`,
    createStudyProgramme: () => `${apiUrl}/study-programmes/create`,
    updateStudyProgramme: (id: string) => `${apiUrl}/study-programmes/${id}/update`,
};
