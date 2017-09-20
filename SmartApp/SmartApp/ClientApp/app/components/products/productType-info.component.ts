// ======================================
// Author: Ebenezer Monney
// Email:  info@ebenmonney.com
// Copyright (c) 2017 www.ebenmonney.com
// 
// ==> Gun4Hire: contact@ebenmonney.com
// ======================================

import { Component, OnInit, ViewChild, Input } from '@angular/core';

import { AlertService, MessageSeverity } from '../../services/alert.service';
import { ProductService } from "../../services/product.service";
import { Utilities } from '../../services/utilities';
import { User } from '../../models/user.model';
import { ProductType } from '../../models/producttype.model';
import { Role } from '../../models/role.model';
import { Permission } from '../../models/permission.model';


@Component({
    selector: 'productType-info',
    templateUrl: './productType-info.component.html',
    styleUrls: ['./productType-info.component.css']
})
export class ProductTypeInfoComponent{

    private isEditMode = false;
    private isNewProductType = false;
    private isSaving = false;
    private showValidationErrors = false;
    private formResetToggle = true;
    private editingProductTypeName: string;
    private uniqueId: string = Utilities.uniqueId();
    private productTypeEdit: ProductType = new ProductType();

    public changesSavedCallback: () => void;
    public changesFailedCallback: () => void;
    public changesCancelledCallback: () => void;

    @ViewChild('f')
    private form;

    constructor(private alertService: AlertService, private productService: ProductService) {
    }

    private showErrorAlert(caption: string, message: string) {
        this.alertService.showMessage(caption, message, MessageSeverity.error);
    }

    private save() {
        this.isSaving = true;
        this.alertService.startLoadingMessage("Saving changes...");

        if (this.isNewProductType) {
            this.productService.newProductType(this.productTypeEdit).subscribe(productType => this.saveSuccessHelper(productType), error => this.saveFailedHelper(error));
        }
        else {
            this.productService.updateProductType(this.productTypeEdit).subscribe(productType => this.saveSuccessHelper(), error => this.saveFailedHelper(error));
        }
    }


    private saveSuccessHelper(productType?: ProductType) {
        if (productType)
            Object.assign(this.productTypeEdit, productType);

        this.isSaving = false;
        this.alertService.stopLoadingMessage();
        this.showValidationErrors = false;

         if (this.isNewProductType)
            this.alertService.showMessage("Success", `Product Type \"${this.productTypeEdit.name}\" was created successfully`, MessageSeverity.success);
        else
             this.alertService.showMessage("Success", `Changes to Product Type \"${this.productTypeEdit.name}\" was saved successfully`, MessageSeverity.success);

        this.productTypeEdit = new Role();
        this.resetForm();

        if (this.changesSavedCallback) {
            this.changesSavedCallback();
        }
    }


    private saveFailedHelper(error: any) {
        this.isSaving = false;
        this.alertService.stopLoadingMessage();
        this.alertService.showStickyMessage("Save Error", "The below errors occured whilst saving your changes:", MessageSeverity.error, error);
        this.alertService.showStickyMessage(error, null, MessageSeverity.error);

        if (this.changesFailedCallback)
            this.changesFailedCallback();
    }


    private cancel() {
        this.productTypeEdit = new ProductType();

        this.showValidationErrors = false;
        this.resetForm();

        this.alertService.showMessage("Cancelled", "Operation cancelled by user", MessageSeverity.default);
        this.alertService.resetStickyMessage();

        if (this.changesCancelledCallback)
            this.changesCancelledCallback();
    }
    
    resetForm(replace = false) {
        if (!replace) {
            this.form.reset();
        }
        else {
            this.formResetToggle = false;

            setTimeout(() => {
                this.formResetToggle = true;
            });
        }
    }


    newProductType() {
        this.isNewProductType = true;
        this.showValidationErrors = true;

        this.editingProductTypeName = null;
        this.productTypeEdit = new ProductType();

        return this.productTypeEdit;
    }

    editProductType(productType: ProductType) {
        if (productType) {
            this.isNewProductType = false;
            this.showValidationErrors = true;

            this.editingProductTypeName = productType.name;
            this.productTypeEdit = new ProductType();
            Object.assign(this.productTypeEdit, productType);

            return this.productTypeEdit;
        }
        else {
            return this.newProductType();
        }
    }

    get canManageProductTypes() {
        return true;
    }
}
