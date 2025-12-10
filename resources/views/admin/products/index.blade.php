@extends('layouts.admin')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Summary Cards - Consistent */
    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .summary-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* CSV Import Button - Matching Add Product Button */
    .btn-import-csv {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-import-csv:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-import-csv:active {
        transform: translateY(0);
    }

    /* Add Product Button */
    .btn-add-product {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-add-product:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-add-product:active {
        transform: translateY(0);
    }

    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }
    
    /* Center align all table headers and cells */
    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }
    
    /* Product column specific styling - keep content readable */
    .table td.name-col {
        text-align: left;
        padding-left: 0.75rem;
    }
    
    .table td.name-col .product-name,
    .table td.name-col .product-sku {
        text-align: left;
        display: block;
        width: 100%;
    }
    
    .table th.name-col {
        text-align: center;
    }
    
    /* Ensure image column is properly centered */
    .table td.image-col,
    .table th.image-col {
        text-align: center;
        padding: 0.75rem 0.25rem;
    }
    
    .table td.image-col .product-img {
        margin: 0 auto;
        display: block;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Status Styling - Text with pulsing dots */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-active {
        color: #2C8F0C;
    }
    
    .status-active::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-inactive {
        color: #6c757d;
    }
    
    .status-inactive::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-archived {
        color: #6c757d;
        font-style: italic;
    }
    
    .status-featured {
        color: #2C8F0C;
        font-weight: 600;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    /* Stock Level Styling */
    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .stock-low {
        color: #FBC02D;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .stock-out {
        color: #C62828;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Filter Section - Consistent */
    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .position-relative {
        position: relative;
    }

    /* Table styling for no scroll bars */
    .table {
        width: 100%;
        max-width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    /* Prevent any scroll bars in the table card */
    .card-custom .card-body {
        overflow-x: hidden;
        overflow-y: hidden;
    }

    .card-custom {
        overflow: hidden;
    }

    /* Responsive table - always fixed layout for better fit */
    .table {
        table-layout: fixed;
    }

    /* Column widths */
    .id-col { width: 60px; min-width: 60px; text-align: center; }
    .image-col { width: 70px; min-width: 70px; text-align: center; }
    .name-col { width: 180px; min-width: 180px; text-align: center; }
    .brand-col { width: 100px; min-width: 100px; text-align: center; }
    .category-col { width: 100px; min-width: 100px; text-align: center; }
    .variants-col { width: 80px; min-width: 80px; text-align: center; }
    .price-col { width: 90px; min-width: 90px; text-align: center; }
    .stock-col { width: 80px; min-width: 80px; text-align: center; }
    .status-col { width: 80px; min-width: 80px; text-align: center; }
    .action-col { width: 120px; min-width: 120px; text-align: center; }

    /* Product Image */
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        line-height: 1.2;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 2px;
    }
    


    /* Price Styling */
    .price-current {
        font-weight: 700;
        font-size: 0.9rem;
        color: #2C8F0C;
    }
    
    .price-original {
        color: #6c757d;
        font-size: 0.75rem;
        text-decoration: line-through;
    }
    
    .discount-badge {
        font-size: 0.7rem;
        background-color: #FFEBEE;
        color: #C62828;
        padding: 0.15rem 0.35rem;
        border-radius: 4px;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Action Buttons - Consistent with other pages */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        padding: 2px 0;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        border: 1.5px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .btn-view {
        background-color: white;
        border-color: #4CAF50;
        color: #4CAF50;
    }
    
    .btn-view:hover {
        background-color: #4CAF50;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-edit {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-edit:hover {
        background-color: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-archive {
        background-color: white;
        border-color: #FBC02D;
        color: #FBC02D;
    }
    
    .btn-archive:hover {
        background-color: #FBC02D;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-unarchive {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-unarchive:hover {
        background-color: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Ensure forms don't interfere with button layout */
    .action-buttons form {
        display: inline;
        margin: 0;
        padding: 0;
    }
    
    .action-buttons form button {
        margin: 0;
        padding: 0;
    }
    
    /* Prevent action column from wrapping */
    .action-col {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Ensure table cells don't expand */
    .table td.action-col {
        overflow: hidden;
    }
    
    /* Fix table layout for better action button display */
    .table {
        table-layout: fixed;
        word-wrap: break-word;
    }

    /* Modern View Modal Styling */
    .view-modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(44, 143, 12, 0.15);
    }

    /* Header Section */
    .view-header-section {
        background: linear-gradient(135deg, #f8fdf8 0%, #e8f5e6 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #c8e6c9;
    }

    .view-product-showcase {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .view-product-image-container {
        flex-shrink: 0;
    }

    .view-product-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 3px solid #2C8F0C;
        background-color: #f8f9fa;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.15);
        display: block;
    }

    .view-product-info {
        flex: 1;
        min-width: 200px;
    }

    .view-product-name {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .view-product-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .view-product-sku {
        color: #6c757d;
        font-size: 0.9rem;
        font-family: 'SF Mono', Monaco, monospace;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .view-product-id {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Content Sections */
    .view-content-sections {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .view-section {
        background: white;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .view-section:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border-color: #2C8F0C;
    }

    .view-section-header {
        background: linear-gradient(135deg, #E8F5E6, #f8fdf8);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #c8e6c9;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .view-section-header i {
        color: #2C8F0C;
        font-size: 1.1rem;
    }

    .view-section-header h4 {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }

    .view-section-content {
        padding: 1.5rem;
    }

    .view-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .view-info-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .view-info-group.full-width {
        grid-column: 1 / -1;
    }

    .view-info-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .view-info-value {
        color: #212529;
        font-size: 1rem;
        font-weight: 500;
        word-break: break-word;
        line-height: 1.4;
    }

    .view-product-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        background-color: #f8f9fa;
        margin: 0 auto 1rem;
        display: block;
    }

    .view-product-name {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .view-product-sku {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        text-align: center;
        font-family: 'SF Mono', Monaco, monospace;
    }

    .view-product-id {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    /* Compact grid layout for cards */
    .view-cards-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }

    .view-cards-grid .view-info-card:nth-child(1),
    .view-cards-grid .view-info-card:nth-child(2),
    .view-cards-grid .view-info-card:nth-child(3),
    .view-cards-grid .view-info-card:nth-child(4) {
        grid-column: span 1;
    }

    .view-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
    }

    .view-status-active {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #166534;
    }

    .view-status-inactive {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #991B1B;
    }

    .view-status-archived {
        background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
        color: #4B5563;
    }

    .view-price-display {
        background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid #2C8F0C;
        text-align: center;
        margin-top: 1rem;
    }

    .view-current-price {
        font-size: 2rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
    }

    .view-original-price {
        font-size: 1.1rem;
        color: #6c757d;
        text-decoration: line-through;
        margin-top: 0.5rem;
    }

    .view-discount-badge {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .view-variants-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .view-variant-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
        transition: background-color 0.2s ease;
    }

    .view-variant-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .view-variant-image {
        flex-shrink: 0;
    }

    .variant-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .view-variant-details {
        flex: 1;
        min-width: 0;
    }

    .view-variant-item:hover {
        background-color: #f8f9fa;
        margin: 0 -1.25rem;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
        border-radius: 8px;
    }

    .view-variant-item:last-child {
        border-bottom: none;
    }

    .view-variant-name {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .view-variant-price {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .view-variant-discount {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .view-variant-stock {
        color: #2C8F0C;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        font-weight: 600;
    }

    .view-no-variants {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fdf8, #f0f8f0);
        border-radius: 12px;
        border: 1px dashed #c8e6c9;
    }

    .no-variants-icon {
        font-size: 3rem;
        color: #c8e6c9;
        margin-bottom: 1rem;
    }

    .no-variants-text h5 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .no-variants-text p {
        color: #8c9aa5;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .view-product-showcase {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .view-product-image {
            width: 100px;
            height: 100px;
        }

        .view-product-meta {
            justify-content: center;
        }

        .view-info-grid {
            grid-template-columns: 1fr;
        }

        .view-info-group.full-width {
            grid-column: 1 / -1;
        }

        .view-section-content {
            padding: 1rem;
        }

        .view-section-header {
            padding: 0.75rem 1rem;
        }

        .view-section-header h4 {
            font-size: 1rem;
        }
    }

    /* Make table more compact on mobile */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .action-col { 
            width: 100px; 
            min-width: 100px; 
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
        
        .action-buttons {
            gap: 2px;
        }
        
        .product-img {
            width: 50px;
            height: 50px;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.8rem;
        }
        
        .status-text {
            font-size: 0.8rem;
        }
    }
    
    /* Extra small screens - stack action buttons vertically */
    @media (max-width: 576px) {
        .action-col { 
            width: 90px; 
            min-width: 90px; 
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 2px;
            align-items: center;
        }
        
        .action-btn {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->total() }}</div>
            <div class="summary-label">Total Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_active', true)->where('is_archived', false)->count() }}</div>
            <div class="summary-label">Active Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_archived', true)->count() }}</div>
            <div class="summary-label">Archived</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_featured', true)->where('is_archived', false)->count() }}</div>
            <div class="summary-label">Featured</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name, description, or SKU...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="brand" class="form-label fw-bold">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" 
                            value="{{ request('brand') }}" placeholder="Filter by brand...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status', 'active') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- CSV Upload Modal -->
<div class="modal fade" id="csvUploadModal" tabindex="-1" aria-labelledby="csvUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-csv me-2"></i>Upload Products via CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.import.csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                @csrf
                <div class="modal-body">
                    
                    <!-- Template Download Section -->
                    <div class="template-download" style="background: #e8f5e9; border: 1px dashed #2C8F0C; padding: 15px; text-align: center; border-radius: 8px; margin-bottom: 20px;">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-download me-2"></i>Download CSV Template
                        </h6>
                        <p class="text-muted mb-3">
                            Use our template to ensure your CSV file has the correct format.
                        </p>
                        <a href="{{ route('admin.products.csv.template') }}" class="btn btn-success">
                            <i class="fas fa-file-download me-2"></i>Download Template
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="csv-instructions" style="background: #f8f9fa; border-left: 4px solid #2C8F0C; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                        <h6><i class="fas fa-info-circle me-2"></i>CSV Format Instructions</h6>
                        <ul class="small mb-0">
                            <li>File must be in CSV format (Comma Separated Values)</li>
                            <li>First row must contain column headers</li>
                            <li>Required columns: <code>name</code>, <code>sku</code>, <code>price</code>, <code>category_id</code></li>
                            <li>Optional columns: <code>description</code>, <code>brand_id</code>, <code>stock_quantity</code>, <code>image_url</code></li>
                            <li>Make sure SKU values are unique</li>
                            <li>Category ID must exist in your categories table</li>
                        </ul>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="csv_file" class="form-label fw-bold">Select CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            Only .csv files are allowed. Maximum file size: 10MB
                        </div>
                    </div>

                    <!-- Processing Options -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Duplicate Handling</label>
                                <select class="form-select" name="duplicate_handling">
                                    <option value="skip">Skip duplicates (keep existing)</option>
                                    <option value="update">Update existing products</option>
                                    <option value="overwrite">Overwrite existing products</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Default Status</label>
                                <select class="form-select" name="default_status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar (Hidden by default) -->
                    <div class="progress mb-3" style="height: 20px; display: none;" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="progress-text">0%</span>
                        </div>
                    </div>

                    <!-- Upload Status Messages -->
                    <div id="uploadStatus" class="alert" style="display: none;"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-import-csv" id="uploadCsvBtn">
                        <i class="fas fa-upload me-2"></i>Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content view-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Header Section -->
                <div class="view-header-section">
                    <div class="view-product-showcase">
                        <div class="view-product-image-container">
                            <img src="" alt="Product Image" class="view-product-image" id="viewProductImage">
                        </div>
                        <div class="view-product-info">
                            <div class="view-product-name" id="viewProductName">-</div>
                            <div class="view-product-meta">
                                <span class="view-product-sku" id="viewProductSku">SKU: -</span>
                                <span class="view-product-id" id="viewProductId">Product ID: #-</span>
                            </div>
                            <div id="viewProductStatus" class="view-status-badge view-status-active">
                                Active
                            </div>
                        </div>
                        <!-- Price Display -->
                        <div class="view-price-display" id="viewPriceDisplay" style="display: none;">
                            <div id="viewDiscountBadge" class="view-discount-badge" style="display: none;"></div>
                            <div class="view-current-price" id="viewCurrentPrice">₱0.00</div>
                            <div class="view-original-price" id="viewOriginalPrice" style="display: none;">₱0.00</div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Sections -->
                <div class="view-content-sections">
                    <!-- Product Details Section -->
                    <div class="view-section">
                        <div class="view-section-header">
                            <i class="fas fa-info-circle"></i>
                            <h4>Product Details</h4>
                        </div>
                        <div class="view-section-content">
                            <div class="view-info-grid">
                                <div class="view-info-group">
                                    <div class="view-info-label">Product ID</div>
                                    <div class="view-info-value" id="viewProductIdField">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Product Name</div>
                                    <div class="view-info-value" id="viewProductNameField">-</div>
                                </div>
                                <div class="view-info-group full-width">
                                    <div class="view-info-label">Description</div>
                                    <div class="view-info-value" id="viewProductDescription">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">SKU</div>
                                    <div class="view-info-value" id="viewProductSkuField">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Created</div>
                                    <div class="view-info-value" id="viewProductCreatedAt">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Updated</div>
                                    <div class="view-info-value" id="viewProductUpdatedAt">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category & Brand Section -->
                    <div class="view-section">
                        <div class="view-section-header">
                            <i class="fas fa-tags"></i>
                            <h4>Category & Brand</h4>
                        </div>
                        <div class="view-section-content">
                            <div class="view-info-grid">
                                <div class="view-info-group">
                                    <div class="view-info-label">Category</div>
                                    <div class="view-info-value" id="viewProductCategory">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Brand</div>
                                    <div class="view-info-value" id="viewProductBrand">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Featured</div>
                                    <div class="view-info-value" id="viewProductFeatured">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Pricing Section -->
                    <div class="view-section">
                        <div class="view-section-header">
                            <i class="fas fa-warehouse"></i>
                            <h4>Inventory & Pricing</h4>
                        </div>
                        <div class="view-section-content">
                            <div class="view-info-grid">
                                <div class="view-info-group">
                                    <div class="view-info-label">Has Variants</div>
                                    <div class="view-info-value" id="viewProductHasVariants">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Variant Count</div>
                                    <div class="view-info-value" id="viewProductVariantCount">-</div>
                                </div>
                                <div class="view-info-group" id="viewMainStockRow" style="display: none;">
                                    <div class="view-info-label">Stock Quantity</div>
                                    <div class="view-info-value" id="viewProductStockQuantity">-</div>
                                </div>
                                <div class="view-info-group" id="viewTotalStockRow" style="display: none;">
                                    <div class="view-info-label">Total Stock</div>
                                    <div class="view-info-value" id="viewProductTotalStock">-</div>
                                </div>
                                <div class="view-info-group">
                                    <div class="view-info-label">Base Price</div>
                                    <div class="view-info-value" id="viewProductBasePrice">-</div>
                                </div>
                                <div class="view-info-group" id="viewSalePriceRow" style="display: none;">
                                    <div class="view-info-label">Sale Price</div>
                                    <div class="view-info-value" id="viewProductSalePrice">-</div>
                                </div>
                                <div class="view-info-group" id="viewDiscountRow" style="display: none;">
                                    <div class="view-info-label">Discount</div>
                                    <div class="view-info-value" id="viewProductDiscount">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Variants Section -->
                    <div class="view-section" id="viewVariantsSection" style="display: none;">
                        <div class="view-section-header">
                            <i class="fas fa-layer-group"></i>
                            <h4>Product Variants</h4>
                        </div>
                        <div class="view-section-content">
                            <div id="viewVariantsList">
                                <!-- Variants will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="editFromViewBtn">
                    <i class="fas fa-edit me-1"></i>Edit Product
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Product List</h5>
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                {{-- <i class="fas fa-file-csv"></i> --}}
                Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
                {{-- <i class="fas fa-plus"></i> --}}
                Add Product
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($products->count() > 0)
            <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="image-col">Image</th>
                            <th class="name-col">Product</th>
                            <th class="brand-col">Brand</th>
                            <th class="category-col">Category</th>
                            <th class="variants-col">Variants</th>
                            <th class="price-col">Price</th>
                            <th class="stock-col">Stock</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="id-col">
                                <span class="text-muted">#{{ $product->id }}</span>
                            </td>
                            <td class="image-col">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="product-img">
                            </td>
                            <td class="name-col">
                                <div class="product-name">{{ Str::limit($product->name, 30) }}</div>
                                <div class="product-sku">{{ $product->sku }}</div>
                            </td>
                            <td class="brand-col">
                                @if($product->brand_id && $product->brand)
                                    <span>{{ Str::limit($product->brand->name, 15) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="category-col">
                                <span>{{ Str::limit($product->category->name, 15) }}</span>
                                @if(!$product->category->is_active)
                                    <div class="text-warning small mt-1">Inactive</div>
                                @endif
                            </td>
                            <td class="variants-col">
                                @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                    <span>{{ $product->variants->count() }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="price-col">
                                @if($product->has_discount)
                                    <div class="price-current">₱{{ number_format($product->sale_price, 2) }}</div>
                                    <div class="price-original">₱{{ number_format($product->price, 2) }}</div>
                                @else
                                    <div class="price-current">₱{{ number_format($product->price, 2) }}</div>
                                @endif
                            </td>
                            <td class="stock-col">
                                @if($product->has_variants)
                                    <span class="stock-high">{{ $product->total_stock }}</span>
                                @else
                                    @if($product->stock_quantity > 10)
                                        <span class="stock-high">{{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="stock-low">{{ $product->stock_quantity }}</span>
                                    @else
                                        <span class="stock-out">{{ $product->stock_quantity }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="status-col">
                                @if($product->is_archived)
                                    <span class="status-archived">Archived</span>
                                @else
                                    @if($product->is_effectively_inactive)
                                        <span class="status-text status-inactive">Inactive</span>
                                    @else
                                        <span class="status-text status-active">Active</span>
                                    @endif
                                    @if($product->is_featured)
                                        <div class="status-featured small mt-1">Featured</div>
                                    @endif
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-view viewBtn" data-bs-toggle="modal"
                                        data-bs-target="#viewProductModal" 
                                        data-product='{{ json_encode(array_merge($product->toArray(), ['image_url' => $product->image_url, 'variants' => $product->variants->map(function($variant) { return array_merge($variant->toArray(), ['image_url' => $variant->image_url]); })->toArray()])) }}'
                                        data-title="View Product" title="View Product">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="action-btn btn-edit" 
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->is_archived)
                                        <form action="{{ route('admin.products.unarchive', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn btn-unarchive"
                                                    onclick="return confirm('Are you sure you want to unarchive this product?')"
                                                    title="Unarchive Product">
                                                <i class="fas fa-box-open"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.products.archive', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn btn-archive"
                                                    onclick="return confirm('Are you sure you want to archive this product?')"
                                                    title="Archive Product">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @if($products->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-box"></i>
                <h5 class="text-muted">No Products Found</h5>
                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                        {{-- <i class="fas fa-file-csv"></i> --}}
                        Import CSV
                    </button>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
                        {{-- <i class="fas fa-plus"></i> --}}
                        Add First Product
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const brandInput = document.getElementById('brand');
    const categorySelect = document.getElementById('category_id');
    const statusSelect = document.getElementById('status');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit brand filter with delay
    brandInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit other filters immediately
    categorySelect.addEventListener('change', function() {
        filterForm.submit();
    });

    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    perPageSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Clear loading indicator when form submits
    filterForm.addEventListener('submit', function() {
        searchLoading.style.display = 'none';
    });

    // CSV Upload functionality
    const csvUploadForm = document.getElementById('csvUploadForm');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadCsvBtn = document.getElementById('uploadCsvBtn');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    const progressText = uploadProgress.querySelector('.progress-text');

    if (csvUploadForm) {
        csvUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show progress bar
            uploadProgress.style.display = 'block';
            uploadStatus.style.display = 'none';
            uploadCsvBtn.disabled = true;
            uploadCsvBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            
            // Simulate progress (in real implementation, you'd use AJAX with progress events)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                if (progress <= 100) {
                    progressBar.style.width = progress + '%';
                    progressBar.setAttribute('aria-valuenow', progress);
                    progressText.textContent = progress + '%';
                }
            }, 100);
            
            // Submit the form
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressText.textContent = '100%';
                
                if (data.success) {
                    showUploadStatus('success', data.message);
                    // Refresh the page after 2 seconds to show new products
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showUploadStatus('danger', data.message || 'Upload failed. Please check your CSV file.');
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                showUploadStatus('danger', 'Upload failed: ' + error.message);
            })
            .finally(() => {
                uploadCsvBtn.disabled = false;
                uploadCsvBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload CSV';
            });
        });
        
        function showUploadStatus(type, message) {
            uploadStatus.className = `alert alert-${type}`;
            uploadStatus.innerHTML = message;
            uploadStatus.style.display = 'block';
        }
        
        // Reset form when modal is closed
        document.getElementById('csvUploadModal').addEventListener('hidden.bs.modal', function() {
            csvUploadForm.reset();
            uploadProgress.style.display = 'none';
            uploadStatus.style.display = 'none';
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        });
    }

    /* === View Product === */
    document.querySelectorAll('.viewBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const product = JSON.parse(this.dataset.product);

            // Simple and direct image handling
            const productImage = document.getElementById('viewProductImage');
            const imageUrl = product.image_url || '/images/noproduct.png';
            

            
            productImage.src = imageUrl;
            productImage.alt = product.name || 'Product Image';
            document.getElementById('viewProductName').textContent = product.name || 'N/A';
            document.getElementById('viewProductSku').textContent = `SKU: ${product.sku || 'N/A'}`;
            document.getElementById('viewProductId').textContent = `Product ID: #${product.id}`;

            // Populate status
            const statusElement = document.getElementById('viewProductStatus');
            if (product.is_archived) {
                statusElement.className = 'view-status-badge view-status-archived';
                statusElement.innerHTML = '<i class="fas fa-archive"></i> Archived';
            } else if (product.is_effectively_inactive) {
                statusElement.className = 'view-status-badge view-status-inactive';
                statusElement.innerHTML = '<i class="fas fa-pause-circle"></i> Inactive';
            } else {
                statusElement.className = 'view-status-badge view-status-active';
                statusElement.innerHTML = '<i class="fas fa-check-circle"></i> Active';
            }

            // Handle featured status
            if (product.is_featured) {
                statusElement.innerHTML += ' <i class="fas fa-star text-warning"></i>';
            }

            // Handle variants
            const hasVariants = product.has_variants;
            const variantsArray = product.variants && ((Array.isArray(product.variants) ? product.variants : (product.variants.data || [])));
            const variantsCount = variantsArray ? variantsArray.length : 0;
            
            document.getElementById('viewProductHasVariants').textContent = hasVariants ? 'Yes' : 'No';
            document.getElementById('viewProductVariantCount').textContent = variantsCount.toString();

            // Show/hide stock rows based on variants
            if (hasVariants) {
                document.getElementById('viewMainStockRow').style.display = 'none';
                document.getElementById('viewTotalStockRow').style.display = 'flex';
                document.getElementById('viewProductTotalStock').textContent = product.total_stock || '0';
            } else {
                document.getElementById('viewMainStockRow').style.display = 'flex';
                document.getElementById('viewTotalStockRow').style.display = 'none';
                document.getElementById('viewProductStockQuantity').textContent = product.stock_quantity || '0';
            }

            // Handle variants display
            const variantsSection = document.getElementById('viewVariantsSection');
            const variantsList = document.getElementById('viewVariantsList');
            variantsList.innerHTML = '';
            

            
            if (variantsCount > 0) {
                // Show the variants section
                variantsSection.style.display = 'block';
                
                // Use the variantsArray which we already prepared
                const variants = variantsArray;
                
                variants.forEach((variant, index) => {
                    const variantDiv = document.createElement('div');
                    variantDiv.className = 'view-variant-item';
                    
                    // Handle variant pricing
                    const price = parseFloat(variant.price || variant.current_price || 0);
                    const salePrice = parseFloat(variant.sale_price || 0);
                    const hasVariantDiscount = salePrice > 0 && salePrice < price;
                    const discountPercentage = hasVariantDiscount ? 
                        Math.round(((price - salePrice) / price) * 100) : 0;
                    
                    const currentPrice = hasVariantDiscount ? salePrice : price;
                    const discountText = hasVariantDiscount ? 
                        `<div class="view-variant-discount">
                            <i class="fas fa-tag me-1"></i>
                            ₱${price.toFixed(2)} → ₱${salePrice.toFixed(2)} (${discountPercentage}% off)
                        </div>` : '';
                    
                    // Handle variant stock
                    const stockText = variant.stock_quantity ? 
                        `<div class="view-variant-stock">
                            <i class="fas fa-boxes me-1"></i>
                            Stock: ${variant.stock_quantity}
                        </div>` : '';
                    
                    // Get variant image URL
                    const variantImageUrl = variant.image_url || '/images/noproduct.png';
                    
                    variantDiv.innerHTML = `
                        <div class="view-variant-content">
                            <div class="view-variant-image">
                                <img src="${variantImageUrl}" alt="${variant.variant_name || variant.name || 'Variant'}" 
                                     class="variant-img" onerror="this.src='/images/noproduct.png'">
                            </div>
                            <div class="view-variant-details">
                                <div class="view-variant-name">
                                    <i class="fas fa-cube me-2"></i>
                                    ${variant.variant_name || variant.name || 'Variant ' + (index + 1)}
                                </div>
                                <div class="view-variant-price">
                                    <i class="fas fa-peso-sign me-1"></i>
                                    ₱${currentPrice.toFixed(2)}
                                </div>
                                ${discountText}
                                ${stockText}
                            </div>
                        </div>
                    `;
                    variantsList.appendChild(variantDiv);
                });
                
                console.log('Variants displayed successfully');
            } else {
                // Show message when no variants found
                variantsSection.style.display = 'block';
                variantsList.innerHTML = `
                    <div class="view-no-variants">
                        <div class="no-variants-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="no-variants-text">
                            <h5>No Variants Available</h5>
                            <p>This product does not have any variants. All inventory is managed under the main product.</p>
                        </div>
                    </div>
                `;
                console.log('No variants found, showing message');
            }

            // Handle pricing
            const basePrice = parseFloat(product.price || 0);
            const salePrice = parseFloat(product.sale_price || 0);
            const hasDiscount = product.has_discount && salePrice > 0 && salePrice < basePrice;

            // Show price display
            document.getElementById('viewPriceDisplay').style.display = 'block';
            
            if (hasDiscount) {
                const discountPercentage = Math.round(((basePrice - salePrice) / basePrice) * 100);
                document.getElementById('viewDiscountBadge').style.display = 'inline-block';
                document.getElementById('viewDiscountBadge').textContent = `${discountPercentage}% OFF`;
                document.getElementById('viewCurrentPrice').textContent = `₱${salePrice.toFixed(2)}`;
                document.getElementById('viewOriginalPrice').style.display = 'block';
                document.getElementById('viewOriginalPrice').textContent = `₱${basePrice.toFixed(2)}`;
                
                // Update pricing card - always show both prices
                document.getElementById('viewSalePriceRow').style.display = 'flex';
                document.getElementById('viewDiscountRow').style.display = 'flex';
                document.getElementById('viewProductBasePrice').textContent = `₱${basePrice.toFixed(2)}`;
                document.getElementById('viewProductSalePrice').textContent = `₱${salePrice.toFixed(2)}`;
                document.getElementById('viewProductDiscount').textContent = `${discountPercentage}%`;
            } else {
                document.getElementById('viewDiscountBadge').style.display = 'none';
                document.getElementById('viewCurrentPrice').textContent = `₱${basePrice.toFixed(2)}`;
                document.getElementById('viewOriginalPrice').style.display = 'none';
                
                // Update pricing card - show base price and indicate no sale price
                document.getElementById('viewSalePriceRow').style.display = 'flex';
                document.getElementById('viewDiscountRow').style.display = 'none';
                document.getElementById('viewProductBasePrice').textContent = `₱${basePrice.toFixed(2)}`;
                document.getElementById('viewProductSalePrice').textContent = 'No sale price';
            }

            // Populate product information
            document.getElementById('viewProductIdField').textContent = `#${product.id}`;
            document.getElementById('viewProductNameField').textContent = product.name || 'N/A';
            document.getElementById('viewProductDescription').textContent = product.description || 'No description available';
            document.getElementById('viewProductSkuField').textContent = product.sku || 'N/A';
            document.getElementById('viewProductCreatedAt').textContent = product.created_at ? 
                new Date(product.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }) : 'N/A';
            document.getElementById('viewProductUpdatedAt').textContent = product.updated_at ? 
                new Date(product.updated_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }) : 'N/A';

            // Populate category and brand
            document.getElementById('viewProductCategory').textContent = product.category ? product.category.name : 'No category';
            document.getElementById('viewProductBrand').textContent = product.brand ? product.brand.name : 'No brand';
            document.getElementById('viewProductFeatured').textContent = product.is_featured ? 'Yes' : 'No';

            // Set edit button data for later use
            document.getElementById('editFromViewBtn').dataset.product = this.dataset.product;
        });
    });

    /* === Edit from View Modal === */
    document.getElementById('editFromViewBtn').addEventListener('click', function() {
        const product = JSON.parse(this.dataset.product);

        // Close view modal
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewProductModal'));
        viewModal.hide();

        // Redirect to edit page
        setTimeout(() => {
            window.location.href = `/admin/products/${product.id}/edit`;
        }, 300);
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());
    
    const bgColors = {
        'success': '#2C8F0C',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
    };
    
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-triangle',
        'warning': 'fa-exclamation-circle',
        'info': 'fa-info-circle'
    };
    
    const bgColor = bgColors[type] || bgColors.success;
    const icon = icons[type] || icons.success;
    const textColor = type === 'warning' ? 'text-dark' : 'text-white';
    
    const toast = document.createElement('div');
    toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
    toast.style.cssText = `
        top: 100px;
        z-index: 9999;
        min-width: 300px;
        text-align: center;
    `;
    
    toast.innerHTML = `
        <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
            <div class="d-flex justify-content-center align-items-center p-3">
                <div class="toast-body ${textColor} d-flex align-items-center">
                    <i class="fas ${icon} me-2 fs-5"></i>
                    <span class="fw-semibold">${message}</span>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}
</script>
@endpush
@endsection