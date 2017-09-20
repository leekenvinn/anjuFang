// ======================================
// Author: Ebenezer Monney
// Email:  info@ebenmonney.com
// Copyright (c) 2017 www.ebenmonney.com
// 
// ==> Gun4Hire: contact@ebenmonney.com
// ======================================

using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using DAL;
using SmartApp.ViewModels;
using AutoMapper;
using DAL.Models;
using Microsoft.Extensions.Logging;
using SmartApp.Helpers;
using Microsoft.AspNetCore.Identity;

namespace SmartApp.Controllers
{
    [Route("api/[controller]")]
    public class ProductController : Controller
    {
        private IUnitOfWork _unitOfWork;
        readonly ILogger _logger;
        private const string GetProductTypeByIdActionName = "GetProductTypeById";
        private const string GetProductTypeByNameActionName = "GetProductTypeByName";


        public ProductController(IUnitOfWork unitOfWork, ILogger<CustomerController> logger)
        {
            _unitOfWork = unitOfWork;
            _logger = logger;
        }



        // GET: api/values
        [HttpGet("producttype")]
        public IActionResult Get()
        {
            var productTypes = _unitOfWork.ProductTypes.GetAll();
            return Ok(Mapper.Map<List<ProductTypeViewModel>>(productTypes));
        }

        [HttpGet("producttype/{id}", Name = GetProductTypeByIdActionName)]
        [Produces(typeof(ProductTypeViewModel))]
        public IActionResult GetProductTypeById(int id)
        {
            var productType = _unitOfWork.ProductTypes.Get(id);
            
            if (productType != null)
                return Ok(Mapper.Map<ProductTypeViewModel>(productType));
            else
                return NotFound(id);
        }

        [HttpGet("producttype/{name}", Name = GetProductTypeByNameActionName)]
        [Produces(typeof(ProductTypeViewModel))]
        public IActionResult GetProductTypeByName(string name)
        {
            var productType = _unitOfWork.ProductTypes.Find(pt => pt.Name.Equals(name)).SingleOrDefault();

            if (productType != null)
                return Ok(Mapper.Map<ProductTypeViewModel>(productType));
            else
                return NotFound(name);
        }

        [HttpPost("producttype")]
        public IActionResult Register([FromBody] ProductTypeViewModel productType)
        {
            if (ModelState.IsValid)
            {
                if (GetProductTypeViewModelHelper(productType.Name) != null)
                {
                    AddErrors("Product type named " + productType.Name + " already exists");
                    return BadRequest(ModelState);
                }

                if (productType == null)
                    return BadRequest($"{nameof(productType)} cannot be null");

                ProductType appProductType = Mapper.Map<ProductType>(productType);

                _unitOfWork.ProductTypes.Add(appProductType);
                if (_unitOfWork.SaveChanges() == 1)
                {
                    ProductTypeViewModel productTypeVM = GetProductTypeViewModelHelper(productType.Name);
                    return Ok(productTypeVM);
                }
            }

            return BadRequest(ModelState);
        }

        [HttpPut("producttype/{id}")]
        public IActionResult UpdateProductType(int id, [FromBody] ProductTypeViewModel productType)
        {
            if (ModelState.IsValid)
            {
                if (productType == null)
                    return BadRequest($"{nameof(productType)} cannot be null");

                ProductType appProductType = _unitOfWork.ProductTypes.Get(id);

                if (appProductType == null)
                    return NotFound(id);


                Mapper.Map<ProductTypeViewModel, ProductType>(productType, appProductType);

                if (_unitOfWork.SaveChanges() == 1)
                {
                    return Ok(productType);
                }
            }

            return BadRequest(ModelState);
        }

        [HttpDelete("producttype/{id}")]
        [Produces(typeof(ProductTypeViewModel))]
        public IActionResult DeleteProductType(int id)
        {
            ProductType appProductType = _unitOfWork.ProductTypes.Get(id);
            ProductTypeViewModel productTypeVM = new ProductTypeViewModel();
            Mapper.Map<ProductType, ProductTypeViewModel > (appProductType, productTypeVM);

            if (productTypeVM == null)
                return NotFound(id);

            if (_unitOfWork.Products.Find(p => p.ProductTypeId == appProductType.Id).Any())
                return BadRequest("Product type cannot be deleted. Remove all products from this product type and try again");

            _unitOfWork.ProductTypes.Remove(appProductType);

            if (_unitOfWork.SaveChanges() == 1)
            {
                return Ok(productTypeVM);
            }
            return BadRequest(ModelState);
        }

        private ProductTypeViewModel GetProductTypeViewModelHelper(string productTypeName)
        {
            var productType = _unitOfWork.ProductTypes.Find(pt => pt.Name.Equals(productTypeName)).SingleOrDefault();

            if (productType != null)
                return Mapper.Map<ProductTypeViewModel>(productType);

            return null;
        }

        private void AddErrors(string error)
        {
            ModelState.AddModelError(string.Empty, error);
        }

    }
}
