// ======================================
// Author: Ebenezer Monney
// Email:  info@ebenmonney.com
// Copyright (c) 2017 www.ebenmonney.com
// 
// ==> Gun4Hire: contact@ebenmonney.com
// ======================================

import { Injectable } from '@angular/core';
import { Router, NavigationExtras } from "@angular/router";
import { Http, Headers, Response } from '@angular/http';
import { Observable } from 'rxjs/Observable';
import { Subject } from 'rxjs/Subject';
import 'rxjs/add/observable/forkJoin';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/map';

import { ProductEndpoint } from './product-endpoint.service';
import { AuthService } from './auth.service';
import { ProductType } from '../models/producttype.model';
import { Role } from '../models/role.model';
import { Permission, PermissionNames, PermissionValues } from '../models/permission.model';
import { UserEdit } from '../models/user-edit.model';



export type ProductTypesChangedOperation = "add" | "delete" | "modify";
export type ProductTypesChangedEventArg = { productTypes: ProductType[] | string[], operation: ProductTypesChangedOperation };



@Injectable()
export class ProductService {

    public static readonly productTypeAddedOperation: ProductTypesChangedOperation = "add";
    public static readonly productTypeDeletedOperation: ProductTypesChangedOperation = "delete";
    public static readonly productTypeModifiedOperation: ProductTypesChangedOperation = "modify";

    private _productTypesChanged = new Subject<ProductTypesChangedEventArg>();


    constructor(private router: Router, private http: Http, private authService: AuthService,
        private productEndpoint: ProductEndpoint) {

    }


    getProductTypes() {
        return this.productEndpoint.getProductTypesEndpoint()
            .map((response: Response) => <ProductType[]>response.json());
    }

    newProductType(productType: ProductType) {
        return this.productEndpoint.getNewProductTypeEndpoint(productType)
            .map((response: Response) => <ProductType>response.json());
    }

    updateProductType(productType: ProductType) {
        if (productType.id) {
            return this.productEndpoint.getUpdateProductTypeEndpoint(productType, productType.id)
                .do(data => this.onProductTypesChanged([productType], ProductService.productTypeModifiedOperation));
        }
        else {
            return this.productEndpoint.getProductTypeByNameEndpoint(productType.name)
                .map((response: Response) => <ProductType>response.json())
                .mergeMap(foundProductType => {
                    productType.id = foundProductType.id;
                    return this.productEndpoint.getUpdateProductTypeEndpoint(productType, productType.id)
                })
                .do(data => this.onProductTypesChanged([productType], ProductService.productTypeModifiedOperation));
        }
    }

    getProductTypeByName(productTypeName: string)
    {
        return this.productEndpoint.getProductTypeByNameEndpoint(productTypeName)
            .map((response: Response) => <ProductType>response.json());
    }

    private onProductTypesChanged(productTypes: ProductType[] | string[], op: ProductTypesChangedOperation) {
        this._productTypesChanged.next({ productTypes: productTypes, operation: op });
    }

    deleteProductType(productTypeId: string): Observable<ProductType> {
        return this.productEndpoint.getDeleteProductTypeEndpoint(productTypeId)
            .map((response: Response) => <ProductType>response.json());
    }



    get permissions(): PermissionValues[] {
        return this.authService.userPermissions;
    }

    get currentUser() {
        return this.authService.currentUser;
    }
}