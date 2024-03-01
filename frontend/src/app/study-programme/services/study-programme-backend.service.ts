import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import {
    CreateStudyProgrammeCommand,
    GetAllStudyProgrammesQuery,
    PaginatedStudyProgrammeResponse,
    StudyProgrammeResponse,
    UpdateStudyProgrammeCommand
} from "../../shared/models/generated";
import { API_URLS } from "../../shared/statics/api-urls.static";

@Injectable({
    providedIn: 'root',
})
export class StudyProgrammeBackendService {
    constructor(private readonly http: HttpClient) {
    }

    public getAllStudyProgrammes(params: GetAllStudyProgrammesQuery): Observable<PaginatedStudyProgrammeResponse> {
        // TODO - Use params
        return this.http.get<PaginatedStudyProgrammeResponse>(API_URLS.getAllStudyProgrammes());
    }

    public getStudyProgramme(id: string): Observable<StudyProgrammeResponse> {
        return this.http.get<StudyProgrammeResponse>(API_URLS.getStudyProgramme(id));
    }

    public createStudyProgramme(payload: CreateStudyProgrammeCommand): Observable<StudyProgrammeResponse> {
        return this.http.post<StudyProgrammeResponse>(API_URLS.createStudyProgramme(), payload);
    }

    public updateStudyProgramme(id: string, payload: UpdateStudyProgrammeCommand): Observable<StudyProgrammeResponse> {
        return this.http.post<StudyProgrammeResponse>(API_URLS.updateStudyProgramme(id), payload);
    }
}
