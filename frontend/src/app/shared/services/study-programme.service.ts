import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { PaginatedStudyProgrammeResponse } from "../../models/generated";
import { SortDirection } from "@angular/material/sort";
import { environment } from "../../../environments/environment";

@Injectable({
    providedIn: 'root',
})
export class StudyProgrammeService {
    constructor(private readonly http: HttpClient) {
    }

    getAllStudyProgrammes(
        sort: string,
        order: SortDirection,
        page: number,
        limit: number
    ): Observable<PaginatedStudyProgrammeResponse> {
        //&sort=${sort}&order=${order}
        return this.http.get<PaginatedStudyProgrammeResponse>(
            `${environment.apiUrl}/study-programmes?page=${page}&limit=${limit}`
        );
    }
}
