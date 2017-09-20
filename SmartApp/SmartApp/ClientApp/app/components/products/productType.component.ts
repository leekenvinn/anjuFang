// ======================================
// Author: Ebenezer Monney
// Email:  info@ebenmonney.com
// Copyright (c) 2017 www.ebenmonney.com
// 
// ==> Gun4Hire: contact@ebenmonney.com
// ======================================

import { Component, OnInit, AfterViewInit, TemplateRef, ViewChild, Input } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap/modal';

import { AlertService, DialogType, MessageSeverity } from '../../services/alert.service';
import { AppTranslationService } from "../../services/app-translation.service";
import { ProductService } from "../../services/product.service";
import { Utilities } from "../../services/utilities";
import { ProductType } from '../../models/producttype.model';
import { Permission } from '../../models/permission.model';
import { fadeInOut } from "../../services/animations";
import { ProductTypeInfoComponent } from "./productType-info.component";

@Component({
    selector: 'productType',
    templateUrl: './productType.component.html',
    styleUrls: ['./productType.component.css'],
    animations: [fadeInOut]
})
export class ProductTypeComponent implements OnInit, AfterViewInit {
    columns: any[] = [];
    rows: ProductType[] = [];
    rowsCache: ProductType[] = [];
    loadingIndicator: boolean;
    editedProductType: ProductType;
    sourceProductType: ProductType;
    editingProductTypeName: { name: string };
    
    @ViewChild('indexTemplate')
    indexTemplate: TemplateRef<any>;

    @ViewChild('actionsTemplate')
    actionsTemplate: TemplateRef<any>;

    @ViewChild('editorModal')
    editorModal: ModalDirective;

    @ViewChild('productTypeEditor')
    productTypeEditor: ProductTypeInfoComponent;

    constructor(private alertService: AlertService, private translationService: AppTranslationService, private productService: ProductService) {
    }

    ngOnInit() {

        let gT = (key: string) => this.translationService.getTranslation(key);

        this.columns = [
            { prop: "index", name: '#', width: 40, cellTemplate: this.indexTemplate, canAutoResize: false },
            { prop: 'name', name: gT('product.producttype.Name'), width: 50 },
            { prop: 'description', name: gT('product.producttype.Description'), width: 90 },
            { name: '', width: 130, cellTemplate: this.actionsTemplate, resizeable: false, canAutoResize: false, sortable: false, draggable: false }
        ];

        this.loadData();
    }

    ngAfterViewInit() {

        this.productTypeEditor.changesSavedCallback = () => {
            this.addNewProductTypeToList();
            this.editorModal.hide();
        };

        this.productTypeEditor.changesCancelledCallback = () => {
            this.editedProductType = null;
            this.sourceProductType = null;
            this.editorModal.hide();
        };
    }

    addNewProductTypeToList() {
        if (this.sourceProductType) {
            Object.assign(this.sourceProductType, this.editedProductType);
            this.editedProductType = null;
            this.sourceProductType = null;
        }
        else {
            let productType = new ProductType();
            Object.assign(productType, this.editedProductType);
            this.editedProductType = null;

            let maxIndex = 0;
            for (let pt of this.rowsCache) {
                if ((<any>pt).index > maxIndex)
                    maxIndex = (<any>pt).index;
            }

            (<any>productType).index = maxIndex + 1;
            this.rowsCache.splice(0, 0, productType);
            this.rows.splice(0, 0, productType);
        }
    }

    loadData() {
        this.alertService.startLoadingMessage();
        this.loadingIndicator = true;

        this.productService.getProductTypes().subscribe(productTypes => this.onDataLoadSuccessful(productTypes), error => this.onDataLoadFailed(error));
    }

    onDataLoadSuccessful(productTypes: ProductType[]) {
        this.alertService.stopLoadingMessage();
        this.loadingIndicator = false;
        
        productTypes.forEach((productType, index, productTypes) => {
            (<any>productType).index = index + 1;
        });

        this.rowsCache = [...productTypes];
        this.rows = productTypes;
    }

    onDataLoadFailed(error: any) {
        this.alertService.stopLoadingMessage();
        this.loadingIndicator = false;

        this.alertService.showStickyMessage("Load Error", `Unable to retrieve product types from the server.\r\nErrors: "${Utilities.getHttpResponseMessage(error)}"`,
            MessageSeverity.error, error);
    }

    onSearchChanged(value: string) {
        if (value) {
            value = value.toLowerCase();

            let filteredRows = this.rowsCache.filter(r => {
                let isChosen = !value
                    || r.name.toLowerCase().indexOf(value) !== -1
                    || r.description.toLowerCase().indexOf(value) !== -1;

                return isChosen;
            });

            this.rows = filteredRows;
        }
        else {
            this.rows = [...this.rowsCache];
        }
    }

    onEditorModalHidden() {
        this.editingProductTypeName = null;
        this.productTypeEditor.resetForm(true);
    }

    newProductType() {
        this.editingProductTypeName = null;
        this.sourceProductType = null;
        this.editedProductType = this.productTypeEditor.newProductType();
        this.editorModal.show();
    }

    editProductType(row: ProductType) {
        this.editingProductTypeName = { name: row.name };
        this.sourceProductType = row;
        this.editedProductType = this.productTypeEditor.editProductType(row);
        this.editorModal.show();
    }

    deleteProductType(row: ProductType) {
        this.alertService.showDialog('Are you sure you want to delete the \"' + row.name + '\" product type?', DialogType.confirm, () => this.deleteProductTypeHelper(row));
    }


    deleteProductTypeHelper(row: ProductType) {

        this.alertService.startLoadingMessage("Deleting...");
        this.loadingIndicator = true;

        if (row.id == null || row.id == "")
            this.productService.getProductTypeByName(row.name).subscribe(productType => this.deleteProductTypeHelper(productType));

        this.productService.deleteProductType(row.id)
            .subscribe(results => {
                this.alertService.stopLoadingMessage();
                this.loadingIndicator = false;

                this.rowsCache = this.rowsCache.filter(item => item !== row)
                this.rows = this.rows.filter(item => item !== row)
            },
            error => {
                this.alertService.stopLoadingMessage();
                this.loadingIndicator = false;

                this.alertService.showStickyMessage("Delete Error", `An error occured whilst deleting the product type.\r\nError: "${Utilities.getHttpResponseMessage(error)}"`,
                    MessageSeverity.error, error);
            });
    }

    get canManageProductTypes() {
        return true;
    }
}
