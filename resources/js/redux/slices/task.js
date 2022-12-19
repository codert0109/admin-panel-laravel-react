import { createSlice } from "@reduxjs/toolkit";
import omit from "lodash/omit";
// utils
import axios from "../../utils/axios";
//
import { dispatch } from "../store";

// ----------------------------------------------------------------------

function objFromArray(array, key = "id") {
    return array.reduce((accumulator, current) => {
        accumulator[current[key]] = current;
        return accumulator;
    }, {});
}

const initialState = {
    isLoading: false,
    error: null,
    board: {
        cards: {},
        columns: {},
        columnOrder: [],
    },
};

const slice = createSlice({
    name: "kanban",
    initialState,
    reducers: {
        // START LOADING
        startLoading(state) {
            state.isLoading = true;
        },

        // HAS ERROR
        hasError(state, action) {
            state.isLoading = false;
            state.error = action.payload;
        },

        // GET BOARD
        getBoardSuccess(state, action) {
            state.isLoading = false;
            const board = action.payload;
            const cards = objFromArray(board.cards);
            const columns = objFromArray(board.columns);
            const { columnOrder } = board;
            state.board = {
                cards,
                columns,
                columnOrder,
            };
        },

        // CREATE NEW COLUMN
        createColumnSuccess(state, action) {
            const newColumn = action.payload;
            state.isLoading = false;
            state.board.columns = {
                ...state.board.columns,
                [newColumn.id]: newColumn,
            };
            state.board.columnOrder.push(newColumn.id);
        },

        persistCard(state, action) {
            const columns = action.payload;
            state.board.columns = columns;
        },

        persistColumn(state, action) {
            state.board.columnOrder = action.payload;
        },

        addTask(state, action) {
            const { card, columnId } = action.payload;

            console.log(state.board.cards);
            state.board.cards[card.id] = card;
            console.log(state.board.cards);
            state.board.columns[columnId].tasks.push(card.id);
        },

        deleteTask(state, action) {
            const { cardId, columnId } = action.payload;

            state.board.columns[columnId].tasks = state.board.columns[
                columnId
            ].tasks.filter((id) => id !== cardId);
            state.board.cards = omit(state.board.cards, [cardId]);
        },

        // UPDATE COLUMN
        updateColumnSuccess(state, action) {
            const column = action.payload;

            state.isLoading = false;
            state.board.columns[column.id] = column;
        },

        // DELETE COLUMN
        deleteColumnSuccess(state, action) {
            const { columnId } = action.payload;
            const deletedColumn = state.board.columns[columnId];
            state.isLoading = false;
            state.board.columns = omit(state.board.columns, [columnId]);
            state.board.cards = omit(state.board.cards, [
                ...deletedColumn.tasks,
            ]);
            console.log(deletedColumn, state.board.columns, state.board.cards);
            state.board.columnOrder = state.board.columnOrder.filter(
                (c) => c !== columnId
            );
        },
    },
});

// Reducer
export default slice.reducer;

export const { actions } = slice;

// ----------------------------------------------------------------------

export function getBoard() {
    return async () => {
        dispatch(slice.actions.startLoading());
        try {
            const response = await axios.get("/api/task/board");
            dispatch(slice.actions.getBoardSuccess(response.data));
        } catch (error) {
            dispatch(slice.actions.hasError(error));
        }
    };
}

// ----------------------------------------------------------------------

export function createColumn(newColumn) {
    return async () => {
        dispatch(slice.actions.startLoading());
        try {
            const response = await axios.post(
                "/api/task/columns/new",
                newColumn
            );
            dispatch(
                slice.actions.createColumnSuccess({
                    ...response.data,
                    tasks: [],
                })
            );
        } catch (error) {
            dispatch(slice.actions.hasError(error));
        }
    };
}

// ----------------------------------------------------------------------

export function updateColumn(columnId, updateColumn) {
    return async () => {
        dispatch(slice.actions.startLoading());
        try {
            const response = await axios.post("/api/task/columns/update", {
                columnId,
                updateColumn,
            });
            dispatch(slice.actions.updateColumnSuccess(response.data));
        } catch (error) {
            dispatch(slice.actions.hasError(error));
        }
    };
}

// ----------------------------------------------------------------------

export function deleteColumn(columnId) {
    return async () => {
        dispatch(slice.actions.startLoading());
        try {
            await axios.post("/api/task/columns/delete", { columnId });
            dispatch(slice.actions.deleteColumnSuccess({ columnId }));
        } catch (error) {
            dispatch(slice.actions.hasError(error));
        }
    };
}

// ----------------------------------------------------------------------

export function persistColumn(newColumnOrder) {
    return () => {
        dispatch(slice.actions.persistColumn(newColumnOrder));
    };
}

// ----------------------------------------------------------------------

export function persistCard(columns) {
    return () => {
        dispatch(slice.actions.persistCard(columns));
    };
}

// ----------------------------------------------------------------------

export function addTask({ card, columnId }) {
    return async () => {
        dispatch(slice.actions.startLoading());
        try {
            const creator_id = 1;
            const response = await axios.post("/api/task/tasks/new", {
                card,
                columnId,
                creator_id,
            });
            dispatch(slice.actions.addTask({ card, columnId }));
        } catch (error) {
            dispatch(slice.actions.hasError(error));
        }
    };
}

// ----------------------------------------------------------------------

export function deleteTask({ cardId, columnId }) {
    return (dispatch) => {
        dispatch(slice.actions.deleteTask({ cardId, columnId }));
    };
}
