<style>
.icommerce_wompi_index {
    & .card {
        padding: 1rem 1rem 0;
    }
    & .card-header {
        background-color: transparent;
        padding: 0 0 .6rem 0;
        & .card-header-icon {
            background-color: var(--primary);
            color: #ffffff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        & .card-header-title {
            margin-left: 1rem;
            color: #000000;
        }
    }
    & .card-text {
        font-size: 0.8rem;
    }
    & .information {
        & .list-group-item {
            border:0;
            padding: 4px 0;
            & span {
                font-weight: bold;
            }
        }
    }
    & thead {
        border-radius: 10px;
        & th {
            border: 0;
            padding: 0.5rem 0.7rem;
        }
        & th:first-child {
            border-radius: 10px 0 0 10px;
        }
        & th:last-child {
            border-radius: 0 10px 10px 0;
        }
    }
    & tbody {
        & tr:first-child td {
            border: 0;
            padding-top: 15px;
        }

    }
    & .type {
      font-size: 11px;
      font-weight: 600;
    }
}
#btnPayWompi {
    background-color: var(--primary);
    color: #ffffff;
    font-weight: bold;
    &:hover {
         box-shadow: 1px 1px 8px var(--primary);
    }
    &:focus {
        outline: 0 !important;
        box-shadow: none !important;
    }
}
.waybox-button {
    background-color: var(--info);
    &:hover {
         box-shadow: 1px 1px 8px var(--info);
    }
}
.custom-control-input:checked ~ .custom-control-label::before {
    border-color: var(--info);
    background-color: var(--info);
}
.table th, .table td {
    vertical-align: middle;
}
</style>