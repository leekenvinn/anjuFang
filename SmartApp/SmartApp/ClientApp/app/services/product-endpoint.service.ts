// ======================================
// Author: Ebenezer Monney
// Email:  info@ebenmonney.com
// Copyright (c) 2017 www.ebenmonney.com
// 
// ==> Gun4Hire: contact@ebenmonney.com
// ======================================

import { Injectable, Injector } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map';

import { EndpointFactory } from './endpoint-factory.service';
import { ConfigurationService } from './configuration.service';


@Injectable()
export class ProductEndpoint extends EndpointFactory {

    private readonly _productsUrl: string = "/api/product/producttype";
    
    get productsUrl() { return this.configurations.baseUrl + this._productsUrl; }

    constructor(http: Http, configurations: ConfigurationService, injector: Injector) {

        super(http, configurations, injector);
    }


    getProductTypesEndpoint(): Observable<Response> {
        let endpointUrl = this.productsUrl;

        return this.http.get(endpointUrl, this.getAuthHeader())
            .map((response: Response) => {
                return response;
            })
            .catch(error => {
                return this.handleError(error, () => this.getProductTypesEndpoint());
            });
    }

    getNewProductTypeEndpoint(productTypeObject: any): Observable<Response> {

        return this.http.post(this.productsUrl, JSON.stringify(productTypeObject), this.getAuthHeader(true))
            .map((response: Response) => {
                return response;
            })
            .catch(error => {
                return this.handleError(error, () => this.getNewProductTypeEndpoint(productTypeObject));
            });
    }

    getProductTypeByNameEndpoint(productTypeName: string): Observable<Response> {
        let endpointUrl = `${this.productsUrl}/${productTypeName}`;

        return this.http.get(endpointUrl, this.getAuthHeader())
            .map((response: Response) => {
                return response;
            })
            .catch(error => {
                return this.handleError(error, () => this.getProductTypeByNameEndpoint(productTypeName));
            });
    }

    getUpdateProductTypeEndpoint(productTypeObject: any, productTypeId: string): Observable<Response> {
        let endpointUrl = `${this.productsUrl}/${productTypeId}`;

        return this.http.put(endpointUrl, JSON.stringify(productTypeObject), this.getAuthHeader(true))
            .map((response: Response) => {
                return response;
            })
            .catch(error => {
                return this.handleError(error, () => this.getUpdateProductTypeEndpoint(productTypeObject, productTypeId));
            });
    }

    getDeleteProductTypeEndpoint(productTypeId: string): Observable<Response> {
        let endpointUrl = `${this.productsUrl}/${productTypeId}`;

        return this.http.delete(endpointUrl, this.getAuthHeader(true))
            .map((response: Response) => {
                return response;
            })
            .catch(error => {
                return this.handleError(error, () => this.getDeleteProductTypeEndpoint(productTypeId));
            });
    }
}