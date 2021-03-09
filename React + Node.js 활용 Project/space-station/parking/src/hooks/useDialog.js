import { useCallback } from "react";
import { useDispatch } from "react-redux";
import { dialogOpen } from "../store/dialog";

export const useDialog = () => {
    const dialogDispatch = useDispatch();
    const openDialog = useCallback((title, text, handleClick, confirm = false, handleBackDrop = false) => {
        dialogDispatch(dialogOpen(confirm, title, text, handleClick, handleBackDrop));
    }, [dialogDispatch]);
    return openDialog;
}