import { useEffect, useRef } from "react";
// @mui
import { Container, Stack } from "@mui/material";
import { DragDropContext, Droppable } from "react-beautiful-dnd";
// redux
import { useDispatch, useSelector } from "../../redux/store";
import { SnackbarProvider } from "notistack";
import { getBoard, persistColumn, persistCard } from "../../redux/slices/task";
// routes
import Page from "../../components/Page";
import SkeletonKanbanColumn from "../../components/skeleton/SkeletonKanbanColumn";
// sections
import {
    KanbanColumn,
    KanbanColumnAdd,
} from "../../sections/@dashboard/kanban";

// ----------------------------------------------------------------------

export default function TaskManager() {
    const dispatch = useDispatch();
    const containerRef = useRef(null);
    const { board } = useSelector((state) => state.kanban);

    useEffect(() => {
        dispatch(getBoard());
    }, [dispatch]);

    const onDragEnd = (result) => {
        // Reorder card
        const { destination, source, draggableId, type } = result;

        if (!destination) return;

        if (
            destination.droppableId === source.droppableId &&
            destination.index === source.index
        )
            return;

        if (type === "column") {
            const newColumnOrder = Array.from(board.columnOrder);
            newColumnOrder.splice(source.index, 1);
            newColumnOrder.splice(destination.index, 0, draggableId);

            dispatch(persistColumn(newColumnOrder));
            return;
        }

        const start = board.columns[source.droppableId];
        const finish = board.columns[destination.droppableId];

        if (start.id === finish.id) {
            const updatedTasks = [...start.tasks];
            updatedTasks.splice(source.index, 1);
            updatedTasks.splice(destination.index, 0, draggableId);

            const updatedColumn = {
                ...start,
                tasks: updatedTasks,
            };

            dispatch(
                persistCard({
                    ...board.columns,
                    [updatedColumn.id]: updatedColumn,
                })
            );
            return;
        }

        const startTasks = [...start.tasks];
        startTasks.splice(source.index, 1);
        const updatedStart = {
            ...start,
            tasks: startTasks,
        };

        const finishTasks = [...finish.tasks];
        finishTasks.splice(destination.index, 0, draggableId);
        const updatedFinish = {
            ...finish,
            tasks: finishTasks,
        };

        dispatch(
            persistCard({
                ...board.columns,
                [updatedStart.id]: updatedStart,
                [updatedFinish.id]: updatedFinish,
            })
        );
    };

    return (
        <SnackbarProvider>
            <Page title="Kanban" sx={{ height: 1 }}>
                <Container maxWidth={false} sx={{ height: 1 }}>
                    <DragDropContext onDragEnd={onDragEnd} refs={containerRef}>
                        <Droppable
                            droppableId="all-columns"
                            direction="horizontal"
                            type="column"
                        >
                            {(provided) => (
                                <Stack
                                    {...provided.droppableProps}
                                    ref={provided.innerRef}
                                    direction="row"
                                    alignItems="flex-start"
                                    spacing={3}
                                    sx={{
                                        height: "calc(100% - 32px)",
                                        overflowY: "hidden",
                                        p: 2,
                                    }}
                                >
                                    {!board.columnOrder.length ? (
                                        <SkeletonKanbanColumn />
                                    ) : (
                                        board.columnOrder.map(
                                            (columnId, index) => (
                                                <KanbanColumn
                                                    index={index}
                                                    key={columnId}
                                                    column={
                                                        board.columns[columnId]
                                                    }
                                                />
                                            )
                                        )
                                    )}
                                    <KanbanColumnAdd />
                                    {provided.placeholder}
                                </Stack>
                            )}
                        </Droppable>
                    </DragDropContext>
                </Container>
            </Page>
        </SnackbarProvider>
    );
}
